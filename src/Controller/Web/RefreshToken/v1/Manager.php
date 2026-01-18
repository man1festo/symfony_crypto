<?php

namespace App\Controller\Web\RefreshToken\v1;

use App\Application\Security\AuthService;
use Symfony\Component\Security\Core\User\UserInterface;

class Manager
{
    public function __construct(private readonly AuthService $authService)
    {

    }

    public function refreshToken(UserInterface $user): string
    {
        return $this->authService->getUserToken($user->getUserIdentifier());
    }
}
