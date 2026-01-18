<?php

namespace App\Controller\Web\UserForm\v1;

use App\Domain\Entity\User;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EditController extends AbstractController
{
    public function __construct(private readonly Manager $manager)
    {

    }

    #[IsGranted('user_edit', 'user')]
    #[Route('/user-form/{id}', name: 'userEditForm', methods: ['POST', 'GET'])]
    public function __invoke(Request $request, #[MapEntity] User $user): Response
    {
        return $this->render('user-form.twig', $this->manager->getFormData($request, $user));
    }
}
