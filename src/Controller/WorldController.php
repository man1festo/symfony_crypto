<?php

namespace App\Controller;

use App\Infrastructure\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class WorldController extends AbstractController
{
    public function __construct(private readonly OrderRepository $repository)
    {

    }
    public function hello(): Response
    {
        $orders = $this->repository->findOrdersToSaleRecursive(1027);
        dd($orders);
        return new Response('Hello World111!');
    }
}
