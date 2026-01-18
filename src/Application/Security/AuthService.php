<?php

namespace App\Application\Security;

use App\Domain\Services\UserService;
use Firebase\JWT\JWT;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    public function __construct(
        private readonly UserService $userService,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly int $tokenTTL,
        private readonly string $privateKeyPath,
    )
    {

    }

    public function isCredentialsValid(string $login, string $password): bool
    {
        $user = $this->userService->findByLogin($login);
        if (!$user) {
            return false;
        }
        return $this->passwordHasher->isPasswordValid($user, $password);
    }

    public function getUserToken(string $login): string
    {
        $user = $this->userService->findByLogin($login);
        $refreshToken = $this->userService->updateUserToken($login);
        $tokenData = [
            'login' => $login,
            'roles' => $user->getRoles(),
            'exp' => time() + $this->tokenTTL,
            'id' => $user->getId(),
            'refresh_token' => $refreshToken,
        ];
        $privateKey = file_get_contents($this->privateKeyPath);
        return JWT::encode($tokenData, $privateKey, 'RS256');
    }
}
