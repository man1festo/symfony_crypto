<?php

declare(strict_types=1);

namespace UnitTests\Services;

use App\Domain\Entity\Account;
use App\Domain\Entity\User;
use App\Domain\Model\CreateAccountModel;
use App\Domain\Model\DepositeIntoAccountModel;
use App\Domain\Services\AccountService;
use App\Domain\Services\UserService;
use App\Infrastructure\Repository\AccountRepository;
use App\Domain\ValueObject\CurrencyEnum;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AccountServiceTest extends TestCase
{
    private AccountRepository|MockObject $accountRepository;
    private UserService|MockObject $userService;
    private AccountService $service;

    protected function setUp(): void
    {
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->userService = $this->createMock(UserService::class);

        $this->service = new AccountService($this->accountRepository, $this->userService);
    }

    /** @test */
    public function testCreatesAccountSuccessfully(): void
    {
        $model = new CreateAccountModel(1, CurrencyEnum::Dollar);

        $user = new User();
        $user->setId(1);

        $this->userService->expects($this->once())
            ->method('findUserById')
            ->with(1)
            ->willReturn($user);

        $this->accountRepository->expects($this->once())
            ->method('create')
            ->with($this->isInstanceOf(Account::class))
            ->willReturn(10);

        $account = $this->service->createAccount($model);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals(10, $account->getId());
        $this->assertSame($user, $account->getUser());
        $this->assertEquals(0, $account->getBalance());
    }

    /** @test */
    public function testFailsToCreateAccountWhenUserNotFound(): void
    {
        $model = new CreateAccountModel(1, CurrencyEnum::Bitcoin);

        $this->userService->expects($this->once())
            ->method('findUserById')
            ->with(1)
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('User not found');

        $this->service->createAccount($model);
    }

    /** @test */
    public function testDepositsIntoAccountSuccessfully(): void
    {
        $model = new DepositeIntoAccountModel(1, 100);

        $account = new Account();
        $account->setId(1);
        $account->setBalance(50);

        $this->accountRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($account);

        $this->accountRepository->expects($this->once())
            ->method('deposite')
            ->with($account, 100);

        $this->accountRepository->expects($this->once())
            ->method('refresh')
            ->with($account);

        $updatedAccount = $this->service->depositeIntoAccount($model);

        $this->assertSame($account, $updatedAccount);
    }

    /** @test */
    public function testFailsToDepositWhenAccountNotFound(): void
    {
        $model = new DepositeIntoAccountModel(1, 100);

        $this->accountRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Account not found');

        $this->service->depositeIntoAccount($model);
    }
}

