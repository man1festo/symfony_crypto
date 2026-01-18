<?php

namespace App\Domain\Model;

use App\Application\Validation\Constraints\EntityExist;
use App\Domain\Entity\Account;
use App\Domain\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class CreateSellOrderModel
{
    public function __construct(
        #[EntityExist(Account::class)]
        public readonly int $accountId,
        #[EntityExist(User::class)]
        public readonly int $userId,
        #[Assert\NotBlank]
        #[Assert\Type('float')]
        public readonly float $amount,
    )
    {

    }
}
