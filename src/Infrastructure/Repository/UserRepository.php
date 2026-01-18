<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;

class UserRepository extends AbstractRepository
{
    public function create(User $user): int
    {
        return $this->store($user);
    }

    public function findUsersByLogin(string $name): array
    {
        return $this->entityManager->getRepository(User::class)->findBy(['login' => $name]);
    }

    public function remove(User $user): void
    {
        $user->setDeletedAt();
        $this->flush();
    }

    public function removeById(int $id): void
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if($user instanceof User) {
            $this->remove($user);
        }
    }

    public function find(int $id): ?User
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if(!$user instanceof User) {
            return null;
        }
        return $user;
    }

    public function approveUser(User $user): void
    {
        $user->setApproved();
        $this->flush();
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    public function updateUserToken(User $user): string
    {
        $token = base64_encode(random_bytes(20));
        $user->setToken($token);
        $this->flush();

        return $token;
    }

    public function findByToken(string $token): ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['token' => $token]);
    }
}
