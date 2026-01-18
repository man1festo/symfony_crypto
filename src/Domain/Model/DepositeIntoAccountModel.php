<?php

namespace App\Domain\Model;

use App\Application\Validation\Constraints\EntityExist;
use App\Domain\Entity\Account;
use JetBrains\PhpStorm\ExpectedValues;
use Symfony\Component\Validator\Constraints as Assert;

class DepositeIntoAccountModel
{
    public function __construct(
        #[EntityExist(Account::class)]
        public readonly int $accountId,
        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Positive]
        public readonly float $amount,
    )
    {

    }
}
