<?php

namespace App\Domain\Doctrine\EntityListeners;

use App\Domain\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Order::class)]
class OrderEntityListener
{

    public function __construct(private readonly LoggerInterface $elasticsearchLogger)
    {

    }
    public function postUpdate(Order $order, PostUpdateEventArgs $args): void
    {
        $this->elasticsearchLogger->debug(sprintf('Post update %s', $order->getId()));
    }
}
