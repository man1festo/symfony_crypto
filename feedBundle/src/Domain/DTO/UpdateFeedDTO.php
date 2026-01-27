<?php

namespace FeedBundle\Domain\DTO;

class UpdateFeedDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $author,
        public readonly int $authorId,
        public readonly string $text,
        public readonly int $userId,
        public readonly int $orderId
    ) {
    }
}
