<?php

namespace App\Domain\Bus;

use App\Domain\DTO\CreateOrdersDTO;

interface CreateOrdersBusInterface
{
    public function sendCreateOrdersMessage(CreateOrdersDTO $createOrdersDTO);
}
