<?php

namespace App\Domain\Entity;

use App\Application\Voter\VoterUserInterface;
use App\Domain\ValueObject\UserRolesEnum;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(name: 'user__login__ind', columns: ['login'])]
#[ORM\UniqueConstraint(name: 'user__login__uniq', columns: ['login'], options: ['where' => '(deleted_at IS NULL)'])]
#[ORM\UniqueConstraint(name: 'user__token__uniq', columns: ['token'])]
class User implements EntityInterface, SoftDeletableInterface, UserInterface, PasswordAuthenticatedUserInterface, VoterUserInterface
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;
    #[ORM\Column(type: 'string', length: 32, nullable: false)]
    private string $login;
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private DateTime $createdAt;
    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private DateTime $updatedAt;
    #[ORM\Column(name: 'deleted_at', type: 'datetime', nullable: true)]
    private DateTime $deletedAt;
    #[ORM\OneToMany(targetEntity: 'Order', mappedBy: 'seller')]
    private Collection $saleOrders;
    #[ORM\OneToMany(targetEntity: 'Order', mappedBy: 'buyer')]
    private Collection $purchaseOrders;
    #[ORM\OneToMany(targetEntity: 'Account', mappedBy: 'user')]
    private Collection $userAccounts;
    #[ORM\Column(type: 'json', length: 1024, nullable: false)]
    private array $roles = [];
    #[ORM\Column(type: 'string', nullable: false)]
    private string $password;
    #[ORM\Column(type: 'string', length: 32, unique: true, nullable: true)]
    private string $token;
    public function __construct()
    {
        $this->saleOrders = new ArrayCollection();
        $this->purchaseOrders = new ArrayCollection();
        $this->userAccounts = new ArrayCollection();
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTime();
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getSaleOrders(): Collection
    {
        return $this->saleOrders;
    }

    public function setSaleOrders(Collection $saleOrders): void
    {
        $this->saleOrders = $saleOrders;
    }

    public function getPurchaseOrders(): Collection
    {
        return $this->purchaseOrders;
    }

    public function setPurchaseOrders(Collection $purchaseOrders): void
    {
        $this->purchaseOrders = $purchaseOrders;
    }

    public function getOrders(): Collection
    {
        return new ArrayCollection(array_merge($this->purchaseOrders->toArray(), $this->saleOrders->toArray()));
    }

    public function getUserAccounts(): Collection
    {
        return $this->userAccounts;
    }

    public function setUserAccounts(Collection $userAccounts): void
    {
        $this->userAccounts = $userAccounts;
    }

    public function setDeletedAt(): void
    {
        $this->deletedAt = new DateTime();
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
            'saleOrders' => array_map(static fn(Order $order) => $order->toArray(), $this->saleOrders->toArray()),
            'purchaseOrders' => array_map(static fn(Order $order) => $order->toArray(), $this->purchaseOrders->toArray()),
            'userAccounts' => array_map(static fn(Account $account) => $account->toArray(), $this->userAccounts->toArray()),
            'userRoles' => $this->roles,
        ];
    }

    public function isApproved(): bool
    {
        return in_array(UserRolesEnum::ROLE_APPROVED->value, $this->roles, true);
    }

    public function setApproved(): void
    {
        $this->roles[] = UserRolesEnum::ROLE_APPROVED->value;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = UserRolesEnum::ROLE_USER->value;
        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getUserIdentifier(): string
    {
        return $this->login;
    }

    public function eraseCredentials(): void
    {
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

}
