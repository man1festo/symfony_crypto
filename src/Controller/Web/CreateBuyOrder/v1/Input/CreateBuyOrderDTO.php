<?php

namespace App\Controller\Web\CreateBuyOrder\v1\Input;

use Symfony\Component\Validator\Constraints as Assert;

class CreateBuyOrderDTO
{
    public function __construct(
        #[Assert\Type('integer')]
        #[Assert\NotBlank]
        public readonly int $userId,
        #[Assert\Type('integer')]
        #[Assert\NotBlank]
        public readonly int $accountId,
        #[Assert\Type('float')]
        #[Assert\NotBlank]
        public readonly float $amount,
    )
    {

    }
}
