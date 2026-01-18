<?php

namespace App\Controller\Web\CreateBuyOrder\v1;

use App\Controller\Web\CreateBuyOrder\v1\Input\CreateBuyOrderDTO;
use App\Controller\Web\CreateBuyOrder\v1\Output\CreatedBuyOderDTO;
use App\Domain\Model\CreateBuyOrderModel;
use App\Domain\Services\MakeModelService;
use App\Domain\Services\OrderService;
use App\Infrastructure\Repository\OrderRepository;

class Manager
{
    public function __construct(private readonly OrderService $orderService, private readonly MakeModelService $makeModelService)
    {

    }

    public function createBuyOrder(CreateBuyOrderDTO $createBuyOrderDTO): CreatedBuyOderDTO
    {
        $model = $this->makeModelService->makeModel(CreateBuyOrderModel::class, $createBuyOrderDTO->userId, $createBuyOrderDTO->accountId, $createBuyOrderDTO->amount);
        $order = $this->orderService->createBuyOrder($model);
        return new CreatedBuyOderDTO(
            $order->toArray()
        );
    }

}
