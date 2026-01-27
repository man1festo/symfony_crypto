<?php

namespace FeedBundle\Domain\Model;

use Shared\Domain\Events\EventsTrait;
use Shared\Domain\Model\AggregateRootInterface;
use Shared\Domain\Model\Email;
use Shared\Domain\Model\Name;

class UserModel implements AggregateRootInterface
{
    use EventsTrait;

    private int $id;
    private ?Name $name;
    private ?Email $email;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?Name
    {
        return $this->name;
    }

    public function setName(?Name $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function setEmail(?Email $email): void
    {
        $this->email = $email;
    }
}
