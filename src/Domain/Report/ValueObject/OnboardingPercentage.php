<?php

declare(strict_types=1);

namespace Acme\Domain\Report\ValueObject;

final class OnboardingPercentage
{
    private $percentage;

    private function __construct(int $percentage)
    {
        $this->percentage = $percentage;
    }

    public static function fromInteger(int $percentage): self
    {
        return new self($percentage);
    }

    public function toInteger(): int
    {
        return $this->percentage;
    }
}
