<?php

namespace App\Controller\Web\SetSellerToOrder\v1;

use App\Controller\Web\SetSellerToOrder\v1\Input\SetSellerToOrderDTO;
use App\Domain\Model\SetSellerToOrderModel;
use App\Domain\Services\MakeModelService;
use App\Domain\Services\OrderService;

class Manager
{

    public function __construct(private readonly OrderService $orderService, private readonly MakeModelService $makeModelService)
    {

    }

    public function setSellerToOrder(SetSellerToOrderDTO $DTO): bool
    {
        $model = $this->makeModelService->makeModel(SetSellerToOrderModel::class, $DTO->sellerId, $DTO->accountId, $DTO->orderId );
        return $this->orderService->setSellerToOrder($model);
    }
}
