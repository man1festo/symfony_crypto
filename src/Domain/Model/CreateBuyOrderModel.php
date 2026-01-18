<?php

namespace App\Domain\Model;

use App\Application\Validation\Constraints\EntityExist;
use App\Domain\Entity\Account;
use App\Domain\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class CreateBuyOrderModel
{
    public function __construct(
        #[EntityExist(entityClass: User::class)]
        public readonly int $buyOrderUserId,
        #[EntityExist(entityClass: Account::class)]
        public readonly int $buyOrderAccountId,
        #[Assert\NotBlank]
        #[Assert\Type('float')]
        public readonly float $buyOrderAmount,
    )
    {

    }

}
