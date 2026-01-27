<?php

namespace App\Controller\Web\CreateComment\v1\Output;

use App\Controller\DTO\OutputDTOInterface;

class CreatedCommentDTO implements OutputDTOInterface
{
    public function __construct(
        public readonly int $orderId,
        public readonly string $comment,
        public readonly int $userId,
        public readonly int $id
    )
    {

    }
}
