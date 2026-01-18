<?php

namespace App\Controller\Web\ApproveBuyOrder\v1;

use App\Domain\Entity\Order;
use App\Domain\Services\OrderService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller
{
    public function __construct(private readonly OrderService $orderService)
    {

    }
    #[IsGranted('approve_buy', 'order')]
    #[Route(path: '/order/{id}/approve_buy', name: 'approveBuyOrder', methods: ['POST'])]
    public function __invoke(#[MapEntity] Order $order): Response
    {
        return new JsonResponse(['result' => $this->orderService->approveBuyOrder($order)]);
    }

}
