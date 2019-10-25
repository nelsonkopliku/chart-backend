<?php

declare(strict_types=1);

namespace Acme\Tests\Dummy;

use Acme\Domain\Report\ValueObject\OnboardingPercentage;

final class DummyOnboardingPercentage
{
    public static function generate(int $percentage = 0): OnboardingPercentage
    {
        return OnboardingPercentage::fromInteger($percentage);
    }
}
