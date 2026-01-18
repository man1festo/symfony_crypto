<?php

namespace App\Controller\Web\CreateUser\v1;

use App\Controller\Web\CreateUser\v1\Input\CreateUserDTO;
use App\Controller\Web\CreateUser\v1\Output\CreatedUserDto;
use App\Domain\Entity\Account;
use App\Domain\Entity\Order;
use App\Domain\Entity\User;
use App\Domain\Model\CreateUserModel;
use App\Domain\Services\MakeModelService;
use App\Domain\Services\UserService;

class Manager
{
    public function __construct(private readonly MakeModelService $makeModelService, private readonly UserService  $userService)
    {
    }

    public function createUser(CreateUserDTO $createUserDTO): ?CreatedUserDto
    {
        $model = $this->makeModelService->makeModel(CreateUserModel::class, $createUserDTO->login, $createUserDTO->password);
        $user = $this->userService->create($model);
        return new CreatedUserDto(
            $user->getLogin(),
            $user->getId(),
            $user->getCreatedAt(),
            $user->getUpdatedAt(),
            array_map(static fn(Order $order) => $order->toArray(), $user->getSaleOrders()->toArray()),
            array_map(static fn(Order $order) => $order->toArray(), $user->getPurchaseOrders()->toArray()),
            array_map(static fn(Account $account) => $account->toArray(), $user->getUserAccounts()->toArray())
        );
    }
}
