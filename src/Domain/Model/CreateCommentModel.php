<?php

namespace App\Domain\Model;

class CreateCommentModel
{
    public function __construct(
        public readonly int $orderId,
        public readonly int $userId,
        public readonly string $comment
    )
    {

    }
}
