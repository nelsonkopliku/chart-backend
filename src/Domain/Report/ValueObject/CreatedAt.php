<?php

declare(strict_types=1);

namespace Acme\Domain\Report\ValueObject;

final class CreatedAt
{
    private $createdAt;

    private function __construct(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public static function fromDateTime(\DateTime $createdAt): self
    {
        return new self($createdAt);
    }

    public static function fromString(string $dateTime): self
    {
        return new self(new \DateTime($dateTime));
    }

    public function toDateTime(): \DateTime
    {
        return $this->createdAt;
    }
}
