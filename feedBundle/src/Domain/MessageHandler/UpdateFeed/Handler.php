<?php

namespace FeedBundle\Domain\MessageHandler\UpdateFeed;

use FeedBundle\Domain\DTO\UpdateFeedDTO;
use FeedBundle\Domain\Model\CommentModel;
use FeedBundle\Domain\Model\OrderModel;
use FeedBundle\Domain\Model\UserModel;
use FeedBundle\Domain\Services\FeedService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class Handler
{
    public function __construct(private readonly MessageBusInterface $messageBus, private readonly FeedService $feedService)
    {

    }

    public function __invoke(UpdateFeedDTO $updateFeedDTO): void
    {
        $comment = new CommentModel($updateFeedDTO->id, new OrderModel($updateFeedDTO->orderId), new UserModel($updateFeedDTO->authorId), $updateFeedDTO->text);
        $this->feedService->addComment($comment, $updateFeedDTO->userId);
    }
}
