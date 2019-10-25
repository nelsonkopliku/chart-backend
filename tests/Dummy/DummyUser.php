<?php

declare(strict_types=1);

namespace Acme\Tests\Dummy;

use Acme\Domain\Report\User;
use Acme\Domain\Report\Users;
use Acme\Domain\Report\ValueObject\CreatedAt;
use Acme\Domain\Report\ValueObject\OnboardingPercentage;

final class DummyUser
{
    public static function createUser(\DateTime $createdAt = null, int $onboardingPercentage = 0): User
    {
        return User::create(
            DummyCreatedAt::generate($createdAt), DummyOnboardingPercentage::generate($onboardingPercentage)
        );
    }

    public static function createCountableUsers(int $howMany = 10): Users
    {
        $users = [];
        for ($i = 0; $i < $howMany; $i++) {
            $users[] = User::create(
                DummyCreatedAt::generate(), DummyOnboardingPercentage::generate()
            );
        }

        return Users::fromArray($users);
    }

    public static function createUsers(): Users
    {
        return Users::fromArray([
            User::create(
                DummyCreatedAt::generate(), DummyOnboardingPercentage::generate()
            )
        ]);
    }

    public static function fromDummySource(string $source = 'source.csv'): Users
    {
        # Similar code in tests/Dummy/DummyRow.php::fromDummySource
        # This should be refactored
        $users = [];
        $row = 0;
        if (($handle = fopen(__DIR__ . '/samples/' . $source, 'rb')) !== false) {
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                if (0 === $row) {
                    $row++;
                    continue;
                }

                $users[] = User::create(
                    CreatedAt::fromString($data[1]), OnboardingPercentage::fromInteger((int) $data[2])
                );
                $row++;
            }
            fclose($handle);
        }
        return Users::fromArray($users);
    }
}
