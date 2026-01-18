<?php

namespace App\Controller\Web\SetBuyerToOrder\v1\Input;

class SetBuyerToOrderDTO
{
    public function __construct(
        public readonly int $buyerId,
        public readonly int $orderId,
        public readonly int $accountId,
    )
    {

    }
}
