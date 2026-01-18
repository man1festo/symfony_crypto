<?php

namespace App\Controller\Web\CreateBuyOrder\v1;

use App\Controller\Web\CreateBuyOrder\v1\Input\CreateBuyOrderDTO;
use App\Controller\Web\CreateBuyOrder\v1\Output\CreatedBuyOderDTO;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller
{

    public function __construct(private readonly Manager $manager)
    {

    }

    #[IsGranted('ROLE_APPROVED')]
    #[Route(path: '/api/v1/order/buy/', name: 'createBuyOrder', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateBuyOrderDTO $orderDTO): CreatedBuyOderDTO
    {
        return $this->manager->createBuyOrder($orderDTO);
    }

}
