<?php

declare(strict_types=1);

namespace Acme\Tests\Domain\Report;

use Acme\Domain\Report\GroupedUsers;
use Acme\Domain\Report\Users;
use Acme\Tests\Dummy\DummyUser;
use PHPUnit\Framework\TestCase;

final class UsersTest extends TestCase
{
    public function test_it_can_be_constructed(): void
    {
        /** Yeah I know, dummy assertion :-) */
        self::assertInstanceOf(Users::class, DummyUser::createUsers());
    }

    public function test_it_returns_all_users_as_array(): void
    {
        $usersList = DummyUser::createCountableUsers();

        self::assertIsArray($usersList->all());
        self::assertCount(10, $usersList->all());
    }

    public function test_it_returns_users_count(): void
    {
        $countableList = DummyUser::createCountableUsers(42);

        self::assertEquals(42, $countableList->count());
    }

    public function test_it_returns_grouped_users(): void
    {
        $users = DummyUser::fromDummySource();

        $groupedByWeek = $users->groupByWeek();

        self::assertInstanceOf(GroupedUsers::class, $groupedByWeek);
        self::assertCount(4, $groupedByWeek->toArray());
        self::assertInstanceOf(Users::class, $groupedByWeek->get('29'));
        self::assertInstanceOf(Users::class, $groupedByWeek->get('30'));
        self::assertInstanceOf(Users::class, $groupedByWeek->get('31'));
        self::assertInstanceOf(Users::class, $groupedByWeek->get('32'));
    }
}
