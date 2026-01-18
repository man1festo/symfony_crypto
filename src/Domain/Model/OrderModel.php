<?php

namespace App\Domain\Model;

class OrderModel
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $sellerLogin,
        public readonly ?string $buyerLogin,
        public readonly ?float $amountToBuy,
        public readonly ?float $amountToSell,
        public readonly \DateTime $createdAt,
    )
    {
    }

}
