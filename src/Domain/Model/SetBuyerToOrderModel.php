<?php

namespace App\Domain\Model;

class SetBuyerToOrderModel
{
    public function __construct(
        public readonly int $buyerId,
        public readonly int $orderId,
        public readonly int $accountId,
    )
    {

    }
}
