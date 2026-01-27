<?php

namespace App\Controller\Web\CreateComment\v1;

use App\Controller\Web\CreateComment\v1\Input\CreateCommentDTO;
use App\Controller\Web\CreateComment\v1\Output\CreatedCommentDTO;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller
{
    public function __construct(private readonly Manager $manager)
    {

    }
    #[Route(path: '/api/v1/comment/create', methods: 'POST')]
    public function __invoke(#[MapRequestPayload] CreateCommentDTO $createCommentDTO): CreatedCommentDTO
    {
        return $this->manager->createComment($createCommentDTO);
    }
}
