<?php

declare(strict_types=1);

namespace Acme\Domain\Report;

use Acme\Domain\Report\ValueObject\CreatedAt;
use Acme\Domain\Report\ValueObject\OnboardingPercentage;

final class User
{
    private $createdAt;

    private $onboardingPercentage;

    public function __construct(CreatedAt $createdAt, OnboardingPercentage $onboardingPercentage)
    {
        $this->createdAt = $createdAt;
        $this->onboardingPercentage = $onboardingPercentage;
    }

    public static function create(CreatedAt $createdAt, OnboardingPercentage $onboardingPercentage): self
    {
        return new self($createdAt, $onboardingPercentage);
    }

    public function createdAt(): CreatedAt
    {
        return $this->createdAt;
    }

    public function onboardingPercentage(): OnboardingPercentage
    {
        return $this->onboardingPercentage;
    }
}
