<?php

namespace App\Controller\Web\RenderUserList\v1;

use App\Domain\Entity\User;
use App\Domain\Services\UserService;

class Manager
{
    public function __construct(private readonly UserService $userService)
    {

    }

    public function getUserList(): array
    {
        return array_map(static fn(User $user) => $user->toArray(), $this->userService->findAll());
    }

}
