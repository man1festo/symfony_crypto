<?php

declare(strict_types=1);

namespace UnitTests\Services;

use App\Domain\Entity\Account;
use App\Domain\Entity\Order;
use App\Domain\Entity\User;
use App\Domain\Model\CreateBuyOrderModel;
use App\Domain\Model\CreateSellOrderModel;
use App\Domain\Services\AccountService;
use App\Domain\Services\OrderService;
use App\Domain\Services\UserService;
use App\Infrastructure\Repository\OrderRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    private OrderRepository|MockObject $orderRepository;
    private UserService|MockObject $userService;
    private AccountService|MockObject $accountService;
    private OrderService $service;

    protected function setUp(): void
    {
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->userService = $this->createMock(UserService::class);
        $this->accountService = $this->createMock(AccountService::class);

        $this->service = new OrderService($this->orderRepository, $this->userService, $this->accountService);
    }

    /** @test */
    public function testCreatesBuyOrderAndReturnsOrderWithIdAndAssociations(): void
    {
        $model = new CreateBuyOrderModel(1, 2, 100.0);

        $user = new User();
        $user->setId(1);

        $account = new Account();
        $account->setId(2);

        $this->userService->expects($this->once())
            ->method('findUserById')
            ->with(1)
            ->willReturn($user);

        $this->accountService->expects($this->once())
            ->method('findAccountById')
            ->with(2)
            ->willReturn($account);

        $this->orderRepository->expects($this->once())
            ->method('create')
            ->with($this->isInstanceOf(Order::class))
            ->willReturn(123);

        $order = $this->service->createBuyOrder($model);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(123, $order->getId());
        $this->assertSame($user, $order->getBuyer());
        $this->assertSame($account, $order->getBuyerAccount());
        $this->assertEquals(100.0, $order->getAmountToBuy());
    }

    /** @test */
    public function testCreatesSellOrderAndReturnsOrderWithIdAndAssociations(): void
    {
        $model = new CreateSellOrderModel(3, 4, 200.0);

        // Create account and user matching CreateSellOrderModel(accountId, userId, amount)
        $account = new Account();
        $account->setId(3);

        $user = new User();
        $user->setId(4);

        $this->userService->expects($this->once())
            ->method('findUserById')
            ->with(4)
            ->willReturn($user);

        $this->accountService->expects($this->once())
            ->method('findAccountById')
            ->with(3)
            ->willReturn($account);

        $this->orderRepository->expects($this->once())
            ->method('create')
            ->with($this->isInstanceOf(Order::class))
            ->willReturn(321);

        $order = $this->service->createSellOrder($model);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(321, $order->getId());
        $this->assertSame($user, $order->getSeller());
        $this->assertSame($account, $order->getSellerAccount());
        $this->assertEquals(200.0, $order->getAmountToSell());
    }

    /** @test */
    public function testApproveSellOrder_delegatesToRepositoryAndReturnsTrue(): void
    {
        $order = new Order();

        $this->orderRepository->expects($this->once())
            ->method('approveSell')
            ->with($order);

        $this->assertTrue($this->service->approveSellOrder($order));
    }

    /** @test */
    public function testApproveBuyOrder_delegatesToRepositoryAndReturnsTrue(): void
    {
        $order = new Order();

        $this->orderRepository->expects($this->once())
            ->method('approveBuy')
            ->with($order);

        $this->assertTrue($this->service->approveBuyOrder($order));
    }
}
