<?php

namespace App\Controller\Web\RenderUserList\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager)
    {

    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route(path: '/user', name: 'userList', methods: ['GET'])]
    public function __invoke(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('user-list.twig', ['users' => $this->manager->getUserList()]);
    }
}
