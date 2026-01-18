<?php

namespace App\Controller\Web\CreateSellOrder\v1;

use App\Controller\Web\CreateSellOrder\v1\Input\CreateSellOrederDTO;
use App\Controller\Web\CreateSellOrder\v1\Output\CreatedSellOrderDTO;
use App\Domain\Model\CreateSellOrderModel;
use App\Domain\Services\MakeModelService;
use App\Domain\Services\OrderService;

class Manager
{

    public function __construct(private readonly MakeModelService $makeModelService, private readonly OrderService $orderService)
    {

    }

    public function createSellOrder(CreateSellOrederDTO $createSellOrderDTO): CreatedSellOrderDTO
    {
        $model = $this->makeModelService->makeModel(CreateSellOrderModel::class, $createSellOrderDTO->accountId, $createSellOrderDTO->userId, $createSellOrderDTO->amount);
        $order = $this->orderService->createSellOrder($model);
        return new CreatedSellOrderDTO($order->toArray());
    }
}
