<?php

namespace FeedBundle\Infrastructure\Bus;

enum AmqpExchangeEnum: string
{
    case UpdateFeed = 'update_feed';
}
