<?php

namespace App\Domain\Bus\Adapter;

use App\Domain\Bus\UpdateFeedBusInterface;
use FeedBundle\Domain\DTO\UpdateFeedDTO as FeedBundleUpdateDTO;
use App\Domain\DTO\UpdateFeedDTO;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdateFeedMessageBus implements UpdateFeedBusInterface
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {

    }

    public function sendUpdateFeedMessage(UpdateFeedDTO $updateFeedDTO): bool
    {
        $this->messageBus->dispatch(new FeedBundleUpdateDTO($updateFeedDTO->id, $updateFeedDTO->author, $updateFeedDTO->authorId, $updateFeedDTO->text, $updateFeedDTO->userId, $updateFeedDTO->orderId));
        return true;
    }
}
