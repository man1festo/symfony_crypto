<?php

namespace App\Controller\Web\CreateAccount\v1;

use App\Controller\Web\CreateAccount\v1\Input\CreateAccountDTO;
use App\Controller\Web\CreateAccount\v1\Output\CreatedAccountDTO;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller
{
    public function __construct(private readonly Manager $manager)
    {
    }

    #[Route(path: '/api/v1/account', name: 'create_account', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateAccountDTO $dto): CreatedAccountDTO
    {
        return $this->manager->createAccount($dto);
    }
}
