<?php

declare(strict_types=1);

namespace Acme\Domain\Report;

use Webmozart\Assert\Assert;

final class Users implements \Countable
{
    private $users;

    private function __construct(array $users = [])
    {
        Assert::allIsInstanceOf($users, User::class);

        $this->users = $users;
    }

    public static function fromArray(array $rows): self
    {
        return new self($rows);
    }

    public function groupByWeek(): GroupedUsers
    {
        $groupedBy = new GroupedUsers();

        foreach ($this->users as $user) {
            /** @var User $user */
            $groupedBy->addUserToGroup($user, $user->createdAt()->toDateTime()->format('W'));
        }

        return $groupedBy;
    }

    public function count(): int
    {
        return count($this->users);
    }

    public function all(): array
    {
        return $this->users;
    }
}
