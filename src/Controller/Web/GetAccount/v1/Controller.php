<?php

namespace App\Controller\Web\GetAccount\v1;

use App\Domain\Entity\Account;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller
{
    #[IsGranted('account_view', 'account')]
    #[Route(path: '/api/v1/account/{id}', name: 'get_account', methods: ['GET'])]
    public function __invoke(#[MapEntity] Account $account): Response
    {
        return new JsonResponse($account->toArray());
    }
}
