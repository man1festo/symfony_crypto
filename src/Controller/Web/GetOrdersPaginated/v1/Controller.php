<?php

namespace App\Controller\Web\GetOrdersPaginated\v1;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class Controller
{
    public function __construct(private readonly Manager $manager)
    {

    }

    #[Route(path: '/api/v1/orders', name: 'getOrders', methods: ['GET'])]
    public function __invoke(#[MapQueryParameter] int $page, #[MapQueryParameter] int $limit): Response
    {
        return new JsonResponse(['orders' => $this->manager->getOrdersPaginated($page, $limit)], 200);
    }
}
