<?php

namespace App\Controller\Web\SetBuyerToOrder\v1;

use App\Controller\Web\SetBuyerToOrder\v1\Input\SetBuyerToOrderDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller
{
    public function __construct(private readonly Manager $manager,)
    {

    }

    #[Route('/api/v1/order/set-buyer', name: 'setBuyerToOrder', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] SetBuyerToOrderDTO $DTO): Response
    {
        return new JsonResponse(['status' => $this->manager->setBuyerToOrder($DTO)]);
    }
}
