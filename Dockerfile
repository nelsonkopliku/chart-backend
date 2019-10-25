# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/compose/compose-file/#target

ARG PHP_VERSION=7.2
ARG NGINX_VERSION=1.17

### NGINX
FROM nginx:${NGINX_VERSION}-alpine AS nginx

COPY docker/nginx/conf.d /etc/nginx/conf.d/
COPY public /srv/app/public/

### PHP
FROM php:${PHP_VERSION}-fpm-alpine AS php_app

RUN apk add --no-cache \
        openssh \
		git \
		icu-libs \
		zlib \
		libzip-dev \
		jq

ENV APCU_VERSION 5.1.17
RUN set -eux \
	&& apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		zlib-dev \
	&& docker-php-ext-install -j$(nproc) \
		intl \
		zip \
	&& pecl install \
		apcu-${APCU_VERSION} \
	&& docker-php-ext-enable --ini-name 20-apcu.ini apcu \
	&& docker-php-ext-enable --ini-name 05-opcache.ini opcache \
	&& runDeps="$( \
        scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
            | tr ',' '\n' \
            | sort -u \
            | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
    )" \
    && apk add --no-cache --virtual .api-phpexts-rundeps $runDeps \
	&& apk del .build-deps

RUN curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony && chmod a+x /usr/local/bin/symfony

RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY docker/app/conf.d/app.ini $PHP_INI_DIR/conf.d/app.ini
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY docker/app/docker-entrypoint.sh /usr/local/bin/docker-app-entrypoint
RUN chmod +x /usr/local/bin/docker-app-entrypoint

COPY docker/app/.ssh/config /root/.ssh/config
RUN chmod 400 /root/.ssh/config

WORKDIR /srv/app
ENTRYPOINT ["docker-app-entrypoint"]
CMD ["php-fpm"]

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_MEMORY_LIMIT -1

# Use prestissimo to speed up builds
RUN composer global require "symfony/flex" --prefer-dist --no-progress --no-suggest --classmap-authoritative  --no-interaction

COPY . .
### PROD BUILD
FROM php_app as build_prod

#RUN mkdir -p var/cache var/logs var/sessions \
#    && composer install --prefer-dist --no-dev --no-scripts --no-progress --no-suggest --classmap-authoritative --no-interaction \
#    && composer clear-cache \
#    && chown -R www-data var

### DEV BUILD
FROM php_app as build_development
### DEBUG BUILD
FROM build_development as build_debug

ARG XDEBUG_VERSION=2.7.2
RUN set -eux; \
	apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
	pecl install xdebug-$XDEBUG_VERSION; \
	docker-php-ext-enable xdebug; \
	apk del .build-deps
