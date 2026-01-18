<?php

namespace App\Controller\Web\GetOrdersPaginated\v1\Output;

class OrderDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $buyerLogin,
        public readonly ?string $sellerLogin,
        public readonly ?float $amountToBuy,
        public readonly ?float $amountToSell,
    )
    {

    }
}
