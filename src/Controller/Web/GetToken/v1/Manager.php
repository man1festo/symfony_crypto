<?php

namespace App\Controller\Web\GetToken\v1;

use App\Application\Security\AuthService;
use App\Controller\Exception\UnauthorizeException;
use Symfony\Component\HttpFoundation\Request;

class Manager
{
    public function __construct(
        private readonly AuthService $authService,
    )
    {

    }
    public function getUserToken(Request $request): string
    {
        $login = $request->get('login');
        $password = $request->get('password');
        if (!$login || !$password) {
            throw new UnauthorizeException();
        }
        if (!$this->authService->isCredentialsValid($login, $password)) {
            throw new UnauthorizeException();
        }
        return $this->authService->getUserToken($login);
    }
}
