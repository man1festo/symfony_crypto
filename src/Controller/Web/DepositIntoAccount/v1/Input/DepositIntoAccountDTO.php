<?php

namespace App\Controller\Web\DepositIntoAccount\v1\Input;

use Symfony\Component\Validator\Constraints as Assert;

class DepositIntoAccountDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Type('integer')]
        public readonly int $accountId,
        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Type('float')]
        public readonly float $amount,
    )
    {

    }
}
