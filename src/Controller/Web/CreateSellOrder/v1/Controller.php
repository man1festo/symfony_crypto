<?php

namespace App\Controller\Web\CreateSellOrder\v1;

use App\Controller\Web\CreateSellOrder\v1\Input\CreateSellOrederDTO;
use App\Controller\Web\CreateSellOrder\v1\Output\CreatedSellOrderDTO;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller
{
    public function __construct(private readonly Manager $manager)
    {

    }

    #[IsGranted('ROLE_APPROVED')]
    #[Route(path: '/api/v1/order/sell/', name: 'sell', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateSellOrederDTO $sellOrderDTO): CreatedSellOrderDTO
    {
        return $this->manager->createSellOrder($sellOrderDTO);
    }
}
