<?php

namespace FeedBundle\Controller\Amqp\UpdateFeed;

use FeedBundle\Application\RabbitMq\AbstractConsumer;
use FeedBundle\Controller\Amqp\UpdateFeed\Input\Message;
use FeedBundle\Domain\Model\CommentModel;
use FeedBundle\Domain\Services\FeedService;
use Psr\Log\LoggerInterface;

class Consumer extends AbstractConsumer
{
    public function __construct(
        private readonly FeedService $feedService,
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
        $comment = new CommentModel($message->id, $message->orderId, $message->authorId, $message->text);
        $this->feedService->addComment($comment, $message->userId);
        return self::MSG_REJECT;
    }
}
