<?php

namespace App\Controller\Web\DepositIntoAccount\v1;

use App\Controller\Web\DepositIntoAccount\v1\Input\DepositIntoAccountDTO;
use App\Controller\Web\DepositIntoAccount\v1\Output\DepositedIntoAccountDTO;
use App\Domain\Entity\Account;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller
{
    public function __construct(private readonly Manager $manager)
    {

    }

    #[Route(path: '/api/v1/account/deposite/{id}/', name: 'depositIntoAccount', methods: ['PATCH'])]
    public function __invoke(#[MapRequestPayload] DepositIntoAccountDTO $dto): DepositedIntoAccountDTO
    {
        return $this->manager->deposite($dto);
    }

}
