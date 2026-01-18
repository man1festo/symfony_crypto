<?php

namespace App\Controller\Web\SetBuyerToOrder\v1;

use App\Controller\Web\SetBuyerToOrder\v1\Input\SetBuyerToOrderDTO;
use App\Domain\Model\SetBuyerToOrderModel;
use App\Domain\Services\AccountService;
use App\Domain\Services\MakeModelService;
use App\Domain\Services\OrderService;
use App\Domain\Services\UserService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class Manager
{
    public function __construct(private readonly MakeModelService $makeModelService, private readonly OrderService $orderService,  private readonly AuthorizationCheckerInterface $authChecker)
    {

    }

    public function setBuyerToOrder(SetBuyerToOrderDTO $DTO): bool
    {
        $order = $this->orderService->findOderById($DTO->orderId);
        if (!$this->authChecker->isGranted('set_buyer', $order)) {
            throw new AccessDeniedHttpException();
        }
        $model = $this->makeModelService->makeModel(SetBuyerToOrderModel::class, $DTO->buyerId, $DTO->orderId, $DTO->accountId);
        return $this->orderService->setBuyerToOrder($model);
    }
}
