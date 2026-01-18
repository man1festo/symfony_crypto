<?php

namespace App\Controller\Web\SetSellerToOrder\v1\Input;

class SetSellerToOrderDTO
{
    public function __construct(
        public readonly int $sellerId,
        public readonly int $orderId,
        public readonly int $accountId
    ){}
}
