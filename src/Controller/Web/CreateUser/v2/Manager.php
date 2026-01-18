<?php

namespace App\Controller\Web\CreateUser\v2;

use App\Controller\Web\CreateUser\v2\Input\CreateUserDTO;
use App\Controller\Web\CreateUser\v2\Output\CreatedUserDTO;
use App\Domain\Model\CreateUserModel;
use App\Domain\Services\MakeModelService;
use App\Domain\Services\UserService;

class Manager
{
    public function __construct(private readonly UserService $userService, private readonly MakeModelService $makeModelService)
    {

    }

    public function createUser(CreateUserDTO $dto): CreatedUserDTO
    {
        $model = $this->makeModelService->makeModel(CreateUserModel::class, $dto->login, $dto->password);
        $user = $this->userService->create($model);
        return new CreatedUserDTO($user->getLogin(), $user->getId());
    }
}
