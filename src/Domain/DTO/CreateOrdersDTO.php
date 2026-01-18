<?php

namespace App\Domain\DTO;

class CreateOrdersDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly int $accountId,
        public readonly int $count,
        public readonly int $amount
    )
    {

    }
}
