<?php

namespace FeedBundle\Domain\Model;

use Shared\Domain\Events\EventsTrait;
use Shared\Domain\Model\AggregateRootInterface;

class OrderModel implements AggregateRootInterface
{
    use EventsTrait;

    private int $id;
    private ?UserModel $user;

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

    public function getUser(): ?UserModel
    {
        return $this->user;
    }

    public function setUser(?UserModel $user): void
    {
        $this->user = $user;
    }

}
