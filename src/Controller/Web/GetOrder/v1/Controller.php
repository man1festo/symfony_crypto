<?php

namespace App\Controller\Web\GetOrder\v1;

use App\Domain\Entity\Order;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller
{
    #[IsGranted('view', 'order')]
    public function __invoke(#[MapEntity(id: 'id')] Order $order):Response
    {
        return new JsonResponse($order->toArray());
    }
}
