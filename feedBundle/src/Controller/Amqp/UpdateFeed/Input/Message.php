<?php

namespace FeedBundle\Controller\Amqp\UpdateFeed\Input;

use Symfony\Component\Validator\Constraints as Assert;
class Message
{
    public function __construct(
        #[Assert\Type('numeric')]
        public readonly int $id,
        public readonly string $author,
        #[Assert\Type('numeric')]
        public readonly int $authorId,
        public readonly string $text,
        #[Assert\Type('numeric')]
        public readonly int $userId,
        public readonly int $orderId,
    )
    {

    }

}
