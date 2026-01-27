<?php

namespace App\Controller\Web\CreateComment\v1\Input;

class CreateCommentDTO
{
    public function __construct(
        public readonly int $orderId,
        public readonly string $comment,
        public readonly int $userId
    )
    {

    }
}
