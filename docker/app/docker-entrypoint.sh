#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
    PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-production"
    PHP_FPM_CONF_DIR="/usr/local/etc/php-fpm.d"

    if [ "$APP_ENV" != 'prod' ]; then
        PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-development"
    fi

    ln -sf "$PHP_INI_RECOMMENDED" "$PHP_INI_DIR/php.ini"

    composer install
    mkdir -p var && chown -R www-data var
fi

exec docker-php-entrypoint "$@"
