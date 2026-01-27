<?php

namespace App\Infrastructure\Bus;

enum AmqpExchangeEnum: string
{
    case CreateOrders = 'create_orders';
    case PublishComment = 'publish_comment';
    case UpdateFeed = 'update_feed';
}
