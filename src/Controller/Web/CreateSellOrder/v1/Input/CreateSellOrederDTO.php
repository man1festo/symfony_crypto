<?php

namespace App\Controller\Web\CreateSellOrder\v1\Input;

class CreateSellOrederDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly int $accountId,
        public readonly float $amount,
    )
    {

    }
}
