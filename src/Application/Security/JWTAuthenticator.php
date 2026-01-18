<?php

namespace App\Application\Security;

use App\Controller\Exception\AccessDeniedException;
use App\Domain\Services\UserService;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class JWTAuthenticator extends AbstractAuthenticator
{
    public function __construct(private readonly UserService $userService, private readonly string $publicKeyPath)
    {

    }
    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $authorization = $request->headers->get('Authorization');
        $token = str_starts_with($authorization, 'Bearer ') ? substr($authorization, 7) : null;
        if (empty($token)) {
            throw new UnauthorizedHttpException('Bearer');
        }
        $publicKey = file_get_contents($this->publicKeyPath);
        try {
            $tokenData = (array) JWT::decode($token, new Key($publicKey, 'RS256'));
        } catch (ExpiredException $e) {
            throw new UnauthorizedHttpException('Expired token');
        }
        if (!isset($tokenData['login'])) {
            throw new UnauthorizedHttpException('Bearer');
        }

        return new SelfValidatingPassport(
            new UserBadge($tokenData['login'], fn() => new AuthUser($tokenData))
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw new AccessDeniedException();
    }
}
