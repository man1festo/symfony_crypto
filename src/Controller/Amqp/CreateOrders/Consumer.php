<?php

namespace App\Controller\Amqp\CreateOrders;

use App\Application\RabbitMq\AbstractConsumer;
use App\Controller\Amqp\CreateOrders\Input\Message;
use App\Domain\Model\CreateBuyOrderModel;
use App\Domain\Services\MakeModelService;
use App\Domain\Services\OrderService;

class Consumer extends AbstractConsumer
{
    public function __construct(
        private readonly MakeModelService $makeModelService,
        private readonly OrderService $orderService,
    )
    {

    }
    public function getMessageClass(): string
    {
        return Message::class;
    }

    /** @param Message $message */
    public function handle($message): int
    {
        $amountPerOrder = $message->amount/$message->count;
        for ($i = 0; $i < $message->count; $i++) {
            $model = $this->makeModelService->makeModel(CreateBuyOrderModel::class, $message->userId, $message->accountId, $amountPerOrder);
            $this->orderService->createBuyOrder($model);
        }
        return self::MSG_ACK;
    }
}
