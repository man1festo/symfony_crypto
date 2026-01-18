<?php

namespace App\Controller\Amqp\CreateOrders\Input;

use App\Application\Validation\Constraints\EntityExist;
use App\Domain\Entity\Account;
use App\Domain\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class Message
{
    public function __construct(
        #[Assert\Type('numeric')]
        #[EntityExist(User::class)]
        public readonly int $userId,
        #[Assert\Type('numeric')]
        #[EntityExist(Account::class)]
        public readonly int $accountId,
        #[Assert\Type('numeric')]
        public readonly int $amount,
        #[Assert\Type('numeric')]
        public readonly int $count,
    )
    {

    }
}
