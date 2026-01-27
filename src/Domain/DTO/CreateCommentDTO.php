<?php

namespace App\Domain\DTO;

class CreateCommentDTO
{
    public function __construct(
        public readonly int $orderId,
        public readonly string $comment,
        public readonly string $userId,
    )
    {

    }
}
