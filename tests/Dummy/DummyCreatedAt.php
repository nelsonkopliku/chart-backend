<?php

declare(strict_types=1);

namespace Acme\Tests\Dummy;

use Acme\Domain\Report\ValueObject\CreatedAt;

final class DummyCreatedAt
{
    public static function generate(\DateTime $dateTime = null): CreatedAt
    {
        return CreatedAt::fromDateTime($dateTime ?? new \DateTime('1970-01-01'));
    }
}
