<?php

declare(strict_types=1);

namespace Acme\Tests\Domain\Report;

use Acme\Domain\Report\User;
use Acme\Domain\Report\ValueObject\CreatedAt;
use Acme\Domain\Report\ValueObject\OnboardingPercentage;
use Acme\Tests\Dummy\DummyUser;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{

    public function test_it_can_be_constructed(): void
    {
        /** Yeah I know, dummy assertion :-) */
        self::assertInstanceOf(User::class, DummyUser::createUser());
    }

    public function test_it_returns_created_at(): void
    {
        $createdAt = new \DateTime('2000-01-01');
        $user      = DummyUser::createUser($createdAt);

        self::assertInstanceOf(CreatedAt::class, $user->createdAt());
        self::assertEquals($createdAt, $user->createdAt()->toDateTime());
    }

    public function test_it_returns_onboarding_percentage(): void
    {
        $onboardingPercentage = 56;
        $user                 = DummyUser::createUser(null, $onboardingPercentage);

        self::assertInstanceOf(OnboardingPercentage::class, $user->onboardingPercentage());
        self::assertEquals($onboardingPercentage, $user->onboardingPercentage()->toInteger());
    }
}
