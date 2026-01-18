<?php

namespace App\Domain\Services;

use App\Domain\Bus\CreateOrdersBusInterface;
use App\Domain\DTO\CreateOrdersDTO;
use App\Domain\Entity\Order;
use App\Domain\Event\BuyerAddedEvent;
use App\Domain\Event\OrderApprovedEvent;
use App\Domain\Event\OrderCreatedEvent;
use App\Domain\Event\SellerAddedEvent;
use App\Domain\Model\CreateBuyOrderModel;
use App\Domain\Model\CreateSellOrderModel;
use App\Domain\Model\SetBuyerToOrderModel;
use App\Domain\Model\SetSellerToOrderModel;
use App\Infrastructure\Repository\OrderRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly UserService $userService,
        private readonly AccountService $accountService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly CreateOrdersBusInterface $createOrdersBus,
    )
    {

    }

    public function createBuyOrder(CreateBuyOrderModel $createBuyOrderModel): Order
    {
        $order = new Order();
        $user = $this->userService->findUserById($createBuyOrderModel->buyOrderUserId);
        $account = $this->accountService->findAccountById($createBuyOrderModel->buyOrderAccountId);
        $order->setBuyer($user);
        $order->setBuyerAccount($account);
        $order->setAmountToBuy($createBuyOrderModel->buyOrderAmount);
        $orderId = $this->orderRepository->create($order);
        $order->setId($orderId);
        $this->eventDispatcher->dispatch(new OrderCreatedEvent($orderId, OrderCreatedEvent::BUY_ORDER, $order->getBuyer()->getId()));
        return $order;
    }

    public function createOrdersAsync(CreateOrdersDTO $DTO)
    {
        return $this->createOrdersBus->sendCreateOrdersMessage($DTO) ? $DTO->count : 0;
    }

    public function createSellOrder(CreateSellOrderModel $createSellOrderModel): Order
    {
        $order = new Order();
        $user = $this->userService->findUserById($createSellOrderModel->userId);
        $account = $this->accountService->findAccountById($createSellOrderModel->accountId);
        $order->setSeller($user);
        $order->setSellerAccount($account);
        $order->setAmountToSell($createSellOrderModel->amount);
        $orderId = $this->orderRepository->create($order);
        $order->setId($orderId);
        return $order;
    }

    public function approveSellOrder(Order $order): bool
    {
        $this->orderRepository->approveSell($order);
        $this->eventDispatcher->dispatch(new OrderApprovedEvent($order->getId(), $order->getSeller()->getId(), OrderApprovedEvent::BY_SELLER));
        return true;
    }

    public function approveBuyOrder(Order $order): bool
    {
        $this->orderRepository->approveBuy($order);
        $this->eventDispatcher->dispatch(new OrderApprovedEvent($order->getId(), $order->getBuyer()->getId(), OrderApprovedEvent::BY_BUYER));
        return true;
    }

    public function setBuyerToOrder(SetBuyerToOrderModel $model): bool
    {
        $order = $this->orderRepository->findById($model->orderId);
        $user = $this->userService->findUserById($model->buyerId);
        $account = $this->accountService->findAccountById($model->accountId);
        $this->orderRepository->setBuyerToOrder($order, $user, $account);
        $this->eventDispatcher->dispatch(new BuyerAddedEvent($order->getBuyer()->getId(), $order->getId()));
        return true;
    }

    public function setSellerToOrder(SetSellerToOrderModel $model): bool
    {
        $order = $this->findOderById($model->orderId);
        $user = $this->userService->findUserById($model->sellerId);
        $account = $this->accountService->findAccountById($model->sellerAccount);
        $this->orderRepository->setSellerToOrder($order, $user, $account);
        $this->eventDispatcher->dispatch(new SellerAddedEvent($order->getSeller()->getId(), $order->getId()));
        return true;
    }

    public function findOderById(int $id): ?Order
    {
        return $this->orderRepository->findById($id);
    }

    public function getOrdersPaginated($page = 1, $limit = 10): ?array
    {
        return $this->orderRepository->getOrdersPaginated($page, $limit);
    }
}
