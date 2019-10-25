<?php

declare(strict_types=1);

namespace Acme\Domain\Report;

final class GroupedUsers
{
    private $groups;

    public function __construct()
    {
        $this->groups = [];
    }

    public function addUserToGroup(User $user, string $group): self
    {
        $this->groups[$group][] = $user;

        return $this;
    }

    public function get(string $group): Users
    {
        return array_key_exists($group, $this->groups) ? Users::fromArray($this->groups[$group]) : Users::fromArray([]);
    }

    public function toArray(): array
    {
        return $this->groups;
    }
}
