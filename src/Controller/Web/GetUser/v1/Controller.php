<?php

namespace App\Controller\Web\GetUser\v1;

use App\Domain\Entity\User;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller
{

    #[IsGranted('user_view', 'user')]
    #[Route(path: '/api/v1/user/{id}', name: 'get', methods: ['GET'])]
    public function __invoke(#[MapEntity(id: 'id')] User $user): Response
    {
        return new JsonResponse($user->toArray());
    }
}
