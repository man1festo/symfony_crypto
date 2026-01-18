<?php

namespace App\Domain\Bus\Adapter;

use App\Domain\Bus\CreateOrdersBusInterface;
use App\Domain\DTO\CreateOrdersDTO;
use App\Infrastructure\Bus\AmqpExchangeEnum;
use App\Infrastructure\Bus\RabbitMqBus;

class CreateOrdersRabbitMqBus implements CreateOrdersBusInterface
{
    public function __construct(private readonly RabbitMqBus $rabbitMqBus)
    {

    }

    public function sendCreateOrdersMessage(CreateOrdersDTO $createOrdersDTO)
    {
        $amountPerOne = $createOrdersDTO->amount/$createOrdersDTO->count;
        $messages = [];
        for ($i = 0; $i < $createOrdersDTO->count; $i++) {
            $messages[] = new CreateOrdersDTO(
                $createOrdersDTO->userId,
                $createOrdersDTO->accountId,
                $amountPerOne,
                1
            );
        }
        return $this->rabbitMqBus->publishMultipleToExchange(AmqpExchangeEnum::CreateOrders, $messages);
    }
}
