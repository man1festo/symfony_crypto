<?php

namespace App\Infrastructure\Bus;

enum AmqpExchangeEnum: string
{
    case CreateOrders = 'create_orders';
}
