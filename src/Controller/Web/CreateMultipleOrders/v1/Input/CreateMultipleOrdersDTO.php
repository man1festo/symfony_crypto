<?php

namespace App\Controller\Web\CreateMultipleOrders\v1\Input;

class CreateMultipleOrdersDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly int $amount,
        public readonly int $count,
        public readonly int $accountId,
        public readonly bool $async
    )
    {}
}
