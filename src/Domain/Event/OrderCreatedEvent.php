<?php

namespace App\Domain\Event;

class OrderCreatedEvent
{
    public const BUY_ORDER = 'buy';
    public const SELL_ORDER = 'sell';
    public function __construct(
        public readonly int $orderId,
        public readonly string $type,
        public readonly int $userId,
    )
    {

    }
}
