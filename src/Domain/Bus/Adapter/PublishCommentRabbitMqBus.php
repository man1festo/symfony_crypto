<?php

namespace App\Domain\Bus\Adapter;

use App\Controller\Web\CreateComment\v1\Output\CreatedCommentDTO;
use App\Domain\Bus\PublishCommentBusInterface;
use App\Domain\Model\CommentModel;
use App\Infrastructure\Bus\AmqpExchangeEnum;
use App\Infrastructure\Bus\RabbitMqBus;

class PublishCommentRabbitMqBus implements PublishCommentBusInterface
{
    public function __construct(private readonly RabbitMqBus $rabbitMqBus)
    {

    }

    public function sendPublishCommentMessage(CommentModel $commentModel)
    {
        return $this->rabbitMqBus->publishToExchange(AmqpExchangeEnum::PublishComment, $commentModel);
    }
}
