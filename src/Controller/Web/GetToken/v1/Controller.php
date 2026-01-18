<?php

namespace App\Controller\Web\GetToken\v1;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Controller
{
    public function __construct(private readonly Manager $manager)
    {

    }

    #[Route('/api/v1/get-token', name: 'token', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        return new JsonResponse([ 'token' => $this->manager->getUserToken($request)]);
    }
}
