<?php

namespace App\Application\Security;

use App\Application\Voter\VoterUserInterface;
use App\Domain\ValueObject\UserRolesEnum;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthUser implements UserInterface, VoterUserInterface
{
    private string $login;
    private array $roles;
    private int $id;

    public function __construct(array $credentials)
    {
        $this->login = $credentials['login'];
        $this->roles = array_unique(array_merge($credentials['roles'] ?? [], [UserRolesEnum::ROLE_USER->value]));
        $this->id = $credentials['id'];
    }
    public function getUserIdentifier(): string
    {
        return $this->login;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): string
    {
        return '';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function eraseCredentials(): void
    {

    }

}
