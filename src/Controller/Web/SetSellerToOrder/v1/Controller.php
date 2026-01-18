<?php

namespace App\Controller\Web\SetSellerToOrder\v1;

use App\Controller\Web\SetSellerToOrder\v1\Input\SetSellerToOrderDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller
{
    public function __construct(private readonly Manager $manager)
    {

    }

    #[Route('/api/v1/order/set-seller', name: 'setSellerToOrder')]
    #[isGranted('set_seller', 'order')]
    public function __invoke(#[MapRequestPayload] SetSellerToOrderDTO $dto): Response
    {
        return new JsonResponse(['success' => $this->manager->setSellerToOrder($dto)]);
    }
}
