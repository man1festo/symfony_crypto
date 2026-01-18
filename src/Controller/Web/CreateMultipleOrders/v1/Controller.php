<?php

namespace App\Controller\Web\CreateMultipleOrders\v1;


use App\Controller\Web\CreateMultipleOrders\v1\Input\CreateMultipleOrdersDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller
{
    public function __construct(private readonly Manager $manager)
    {

    }

    #[Route(path: '/api/v1/order/create-multiple', name: 'createMultipleOrders', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateMultipleOrdersDTO $DTO): Response
    {
        return new JsonResponse(['success' => $this->manager->createOrders($DTO)]);
    }
}
