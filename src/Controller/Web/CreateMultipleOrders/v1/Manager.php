<?php

namespace App\Controller\Web\CreateMultipleOrders\v1;

use App\Controller\Web\CreateMultipleOrders\v1\Input\CreateMultipleOrdersDTO;
use App\Domain\DTO\CreateOrdersDTO;
use App\Domain\Model\CreateBuyOrderModel;
use App\Domain\Services\MakeModelService;
use App\Domain\Services\OrderService;

class Manager
{
    public function __construct(private readonly MakeModelService $makeModelService, private readonly OrderService $orderService)
    {
    }

    public function createOrders(CreateMultipleOrdersDTO $createMultipleOrdersDTO): bool
    {
        if ($createMultipleOrdersDTO->async) {
            $this->orderService->createOrdersAsync(new CreateOrdersDTO(
                $createMultipleOrdersDTO->userId,
                $createMultipleOrdersDTO->accountId,
                $createMultipleOrdersDTO->count,
                $createMultipleOrdersDTO->amount
            ));
        } else {
            $amountPerOrder = $createMultipleOrdersDTO->amount / $createMultipleOrdersDTO->count;
            for ($i = 0; $i < $createMultipleOrdersDTO->count; $i++) {
                $model = $this->makeModelService->makeModel(CreateBuyOrderModel::class, $createMultipleOrdersDTO->userId, $createMultipleOrdersDTO->accountId, $amountPerOrder);
                $this->orderService->createBuyOrder($model);
            }
        }
        return true;
    }
}
