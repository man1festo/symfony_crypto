<?php

namespace App\Controller\Amqp\PublishComment;

use App\Application\RabbitMq\AbstractConsumer;
use App\Controller\Amqp\PublishComment\Input\Message;
use App\Domain\Bus\UpdateFeedBusInterface;
use App\Domain\DTO\UpdateFeedDTO;
use App\Domain\Services\OrderService;
use App\Domain\Services\UserService;

class Consumer extends AbstractConsumer
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly UserService $userService,
        private readonly UpdateFeedBusInterface $updateFeedBus,
    )
    {
    }

    public function getMessageClass(): string
    {
        return Message::class;
    }

    /** @param Message $message */
    public function handle( $message): int
    {
        $user = $this->userService->findUserById($message->userId)->getLogin();
        $order = $this->orderService->findOderById($message->orderId);
        $feedUserId = $order->getBuyer()->getId();
        $updateFeedDTO = new UpdateFeedDTO($message->id, $user, $message->userId, $message->comment, $feedUserId, $message->orderId);
        $this->updateFeedBus->sendUpdateFeedMessage($updateFeedDTO);
        return self::MSG_ACK;
    }

}
