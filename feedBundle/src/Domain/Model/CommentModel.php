<?php

namespace FeedBundle\Domain\Model;

use Shared\Domain\Events\EventsTrait;
use Shared\Domain\Model\AggregateRootInterface;

class CommentModel implements AggregateRootInterface
{
    use EventsTrait;

    private int $id;
    private UserModel $user;
    private OrderModel $order;
    private string $comment;
    public function __construct(
        int $id,
        OrderModel $order,
        UserModel $user,
        string $comment
    )
    {
        $this->id = $id;
        $this->user = $user;
        $this->order = $order;
        $this->comment = $comment;
    }

    public function toFeed(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user->getId(),
            'order' => $this->order->getId(),
            'text' => $this->comment,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUser(): UserModel
    {
        return $this->user;
    }

    public function setUser(UserModel $user): void
    {
        $this->user = $user;
    }

    public function getOrder(): OrderModel
    {
        return $this->order;
    }

    public function setOrder(OrderModel $order): void
    {
        $this->order = $order;
    }

}
