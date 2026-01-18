<?php

namespace App\Domain\Model;

use App\Domain\Entity\User;
use App\Domain\ValueObject\CurrencyEnum;
use Symfony\Component\Validator\Constraints as Assert;
use App\Application\Validation\Constraints\EntityExist;

class CreateAccountModel
{
    public function __construct(
        #[Assert\NotBlank]
        #[EntityExist(entityClass: User::class)]
        public readonly int $userId,
        public readonly CurrencyEnum $currency,
    )
    {
    }
}
