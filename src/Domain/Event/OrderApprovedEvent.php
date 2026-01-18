<?php

namespace App\Domain\Event;

class OrderApprovedEvent
{
    public const BY_BUYER = 'buyer';
    public const BY_SELLER = 'seller';
    public function __construct(
        public readonly int $orderId,
        public readonly int $approverId,
        public readonly string $approverRole
    ) {}

}
