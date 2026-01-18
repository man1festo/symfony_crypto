<?php

namespace App\Domain\Event;

class SellerAddedEvent
{
    public function __construct(
        public readonly int $sellerId,
        public readonly int $orderId,
    ){}
}
