<?php

namespace App\Controller\Web\ApproveUser\v1;

use App\Domain\Entity\User;
use App\Domain\Services\UserService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller
{
    public function __construct(private readonly UserService $userService)
    {

    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/api/v1/user/{id}/approve', name: 'approveUser', methods: ['POST'])]
    public function __invoke(#[MapEntity] User $user):Response
    {
        return new JsonResponse($this->userService->approveUser($user));
    }
}
