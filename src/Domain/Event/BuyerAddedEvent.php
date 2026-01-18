<?php

namespace App\Domain\Event;

class BuyerAddedEvent
{
    public function __construct(
        public readonly int $buyerId,
        public readonly int $orderId,
    ){}
}
