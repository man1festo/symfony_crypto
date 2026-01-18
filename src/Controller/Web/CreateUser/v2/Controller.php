<?php

namespace App\Controller\Web\CreateUser\v2;

use App\Controller\Web\CreateUser\v2\Input\CreateUserDTO;
use App\Controller\Web\CreateUser\v2\Output\CreatedUserDTO;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller
{
    public function __construct(private readonly Manager $manager)
    {

    }

    #[Route(path: '/api/v2/user/create', name: 'user', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateUserDTO $dto): CreatedUserDTO
    {
        return $this->manager->createUser($dto);
    }
}
