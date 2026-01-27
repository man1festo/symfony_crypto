<?php

namespace App\Controller\Amqp\PublishComment\Input;

use Symfony\Component\Validator\Constraints as Assert;
class Message
{
    public function __construct(
        #[Assert\Type('numeric')]
        public readonly int $orderId,
        public readonly string $comment,
        #[Assert\Type('numeric')]
        public readonly int $userId,
        #[Assert\Type('numeric')]
        public readonly int $id
    )
    {

    }
}
