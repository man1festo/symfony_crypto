<?php

namespace App\Controller\Web\GetOrdersPaginated\v1;

use App\Controller\Web\GetOrdersPaginated\v1\Output\OrderDTO;
use App\Domain\Entity\Order;
use App\Domain\Model\OrderModel;
use App\Domain\Services\OrderService;

class Manager
{
    public function __construct(private readonly OrderService $orderService)
    {

    }

    public function getOrdersPaginated($page, $limit): array
    {
        $orders = $this->orderService->getOrdersPaginated($page, $limit);
        return array_map(function (OrderModel $order) {
            return new OrderDTO(
                $order->id,
                $order->buyerLogin,
                $order->sellerLogin,
                $order->amountToBuy,
                $order->amountToSell,
            );
        }, $orders);
    }
}
