<?php

declare(strict_types=1);

namespace Acme\Infrastructure\Repository\Csv;

final class Row
{
    private $createdAt;

    private $onboardingPercentage;

    public function __construct(\DateTime $createdAt, string $onboardingPercentage)
    {
        $this->createdAt = $createdAt;
        $this->onboardingPercentage = (int) $onboardingPercentage;
    }

    public function createdAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function onboardingPercentage(): int
    {
        return $this->onboardingPercentage;
    }
}
