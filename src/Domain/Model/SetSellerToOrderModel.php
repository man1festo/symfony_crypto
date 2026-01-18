<?php

namespace App\Domain\Model;

class SetSellerToOrderModel
{
    public function __construct(
        public readonly int $sellerId,
        public readonly int $sellerAccount,
        public readonly int $orderId,
    )
    {

    }
}
