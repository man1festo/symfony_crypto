<?php

namespace App\Domain\Services;

use App\Domain\Entity\User;
use App\Domain\Event\CreateUserEvent;
use App\Domain\Model\CreateUserModel;
use App\Domain\ValueObject\UserRolesEnum;
use App\Infrastructure\Repository\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository, private readonly UserPasswordHasherInterface $passwordEncoder, private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function createUserByLogin(string $login): User
    {
        $user = new User();
        $user->setLogin($login);
        $userId = $this->userRepository->create($user);
        $user->setId($userId);
        return $user;
    }

    public function create(CreateUserModel $createUserModel): User
    {
        if($createUserModel->id){
            $user = $this->userRepository->find($createUserModel->id);
        } else {
            $user = new User();
        }
        $user->setLogin($createUserModel->login);
        $user->setPassword($this->passwordEncoder->hashPassword($user, $createUserModel->password));
        $user->setRoles([UserRolesEnum::ROLE_USER->value]);
        $userId = $this->userRepository->create($user);
        $user->setId($userId);
        $this->eventDispatcher->dispatch(new CreateUserEvent($user->getLogin()));
        return $user;
    }
    public  function findUserById(int $userId): ?User
    {
        return $this->userRepository->find($userId);
    }

    public function approveUser(User $user): bool
    {
        $this->userRepository->approveUser($user);
        return true;
    }

    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function createFromForm(User $user): void
    {
        $this->userRepository->create($user);
    }

    public function findByLogin(string $login): ?User
    {
        $user = $this->userRepository->findUsersByLogin($login);
        return $user[0] ?? null;
    }

    public function updateUserToken(string $login): ?string
    {
        $user = $this->findByLogin($login);
        if (!$user) {
            return null;
        }
        return $this->userRepository->updateUserToken($user);
    }

    public function findByToken(string $token): ?User
    {
        return $this->userRepository->findByToken($token);
    }
}
