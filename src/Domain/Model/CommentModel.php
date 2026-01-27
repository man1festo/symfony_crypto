<?php

namespace App\Domain\Model;

class CommentModel
{
    public function __construct(
        public readonly int $id,
        public readonly int $orderId,
        public readonly int $userId,
        public readonly string $comment
    )
    {

    }

    public function toFeed(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->userId,
            'order' => $this->orderId,
            'text' => $this->comment,
        ];
    }

}
