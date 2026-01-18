<?php

namespace App\Domain\EventSubscriber;

use App\Domain\Event\BuyerAddedEvent;
use App\Domain\Event\OrderApprovedEvent;
use App\Domain\Event\OrderCreatedEvent;
use App\Domain\Event\SellerAddedEvent;
use App\Infrastructure\Storage\MetricsStorage;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $elasticsearchLogger, private readonly MetricsStorage $metricsStorage)
    {

    }
    public static function getSubscribedEvents(): array
    {
        return [
            BuyerAddedEvent::class => ['onBuyerAdded', 0],
            SellerAddedEvent::class => ['onSellerAdded', 0],
            OrderApprovedEvent::class => ['onOrderApproved', 0],
            OrderCreatedEvent::class => ['onOrderCreated', 0],
        ];
    }
    public function onBuyerAdded(BuyerAddedEvent $event): void
    {
        $this->elasticsearchLogger->info(sprintf("Buyer %d added to order %d", $event->buyerId, $event->orderId));
    }

    public function onSellerAdded(SellerAddedEvent $event): void
    {
        $this->elasticsearchLogger->info(sprintf("Seller %d added to order %d", $event->sellerId, $event->orderId));
    }

    public function onOrderApproved(OrderApprovedEvent $event): void
    {
        $this->elasticsearchLogger->info(sprintf("Order %d approved by %s id %d", $event->orderId, $event->approverRole, $event->approverId));
    }

    public function onOrderCreated(OrderCreatedEvent $event): void
    {
        $this->metricsStorage->increment(MetricsStorage::ORDER_CREATED);
        $this->elasticsearchLogger->info(sprintf("%s Order %d created by id %d", $event->type, $event->orderId, $event->userId));
    }
}
