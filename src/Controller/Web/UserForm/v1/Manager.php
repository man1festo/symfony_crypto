<?php

namespace App\Controller\Web\UserForm\v1;

use App\Controller\Form\UserType;
use App\Controller\Web\UserForm\v1\Input\UserFormDTO;
use App\Domain\Entity\User;
use App\Domain\Model\CreateUserModel;
use App\Domain\Services\MakeModelService;
use App\Domain\Services\UserService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class Manager
{
    public function __construct(
        private readonly UserService $userService,
        private readonly FormFactoryInterface $formFactory,
        private readonly MakeModelService $makeModelService)
    {

    }

    public function getFormData(Request $request, ?User $user = null): array
    {
        $isNew = $user === null;
        $userDto = $isNew ? null : new UserFormDTO(
            $user->getLogin(),
            $user->getPassword(),
            $user->getId()
        );
        $form = $this->formFactory->create(UserType::class, $userDto, ['isNew' => $isNew]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userDto = $form->getData();
            $model = $this->makeModelService->makeModel(CreateUserModel::class,
                $userDto->login,
                $userDto->password,
                $userDto->id
            );
            $this->userService->create($model);
        }
        return [
            'form' => $form,
            'isNew' => $isNew,
            'user' => $user,
        ];
    }
}
