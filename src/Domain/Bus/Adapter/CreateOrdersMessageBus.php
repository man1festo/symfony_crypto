<?php

namespace App\Domain\Bus\Adapter;

use App\Domain\Bus\CreateOrdersBusInterface;
use App\Domain\DTO\CreateOrdersDTO;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateOrdersMessageBus implements CreateOrdersBusInterface
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {

    }

    public function sendCreateOrdersMessage(CreateOrdersDTO $createOrdersDTO): void
    {
        $amountPerOne = $createOrdersDTO->amount/$createOrdersDTO->count;
        for ($i = 0; $i < $createOrdersDTO->count; $i++) {
            $this->messageBus->dispatch(new CreateOrdersDTO(
                $createOrdersDTO->userId,
                $createOrdersDTO->accountId,
                $amountPerOne,
                1
            ));
        }
    }
}
