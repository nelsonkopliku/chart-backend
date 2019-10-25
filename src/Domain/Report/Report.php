<?php

declare(strict_types=1);

namespace Acme\Domain\Report;

final class Report
{
    private $rows;

    private function __construct(Users $rows)
    {
        $this->rows = $rows;
    }

    public static function forUsersRows(Users $rows): self
    {
        return new self($rows);
    }

    public function generate(): array
    {
        $totalUsers = $this->rows->count();
        $usersPercentageInStateByWeek = [];

        foreach ($this->rows->groupByWeek()->toArray() as $week => $users) {
            $usersPercentageInStateByWeek[$week] = array_map(
                $this->calculateUsersPercentageInState($totalUsers),
                $this->calculateUsersCountInState($users)
            );
            $usersPercentageInStateByWeek[$week][0] = 100;
            ksort($usersPercentageInStateByWeek[$week]);
        }

        ksort($usersPercentageInStateByWeek);

        return $usersPercentageInStateByWeek;
    }

    private function calculateUsersPercentageInState(int $totalUsers): callable
    {
        return static function($usersCountInState) use ($totalUsers) {
            return round(($usersCountInState / $totalUsers) * 100, 2);
        };
    }

    private function calculateUsersCountInState(array $usersInWeek): array
    {
        $stats = [];

        foreach ($usersInWeek as $user) {
            /** @var User $user */
            $onboardingPercentage = $user->onboardingPercentage()->toInteger();
            $stats[$onboardingPercentage] = ($stats[$onboardingPercentage] ?? null) ? $stats[$onboardingPercentage] + 1 : 1;
        }

        return $stats;
    }
}
