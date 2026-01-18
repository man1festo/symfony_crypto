<?php

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\ValueObject\CurrencyEnum;

#[ORM\Entity]
#[ORM\Table(name: 'account')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'currency', type: 'string', enumType: CurrencyEnum::class)]
#[ORM\DiscriminatorMap(
    [
        CurrencyEnum::Dollar->value => DollarAccount::class,
        CurrencyEnum::Ruble->value => RubleAccount::class,
        CurrencyEnum::Bitcoin->value => BitcoinAccount::class,
        CurrencyEnum::Ethereum->value => EthereumAccount::class,
    ]
)]
#[ORM\Index(name: 'account__user_id__ind', columns: ['user_id'])]
class Account implements EntityInterface, SoftDeletableInterface
{
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;
    #[ORM\ManyToOne(targetEntity: 'User', inversedBy: 'userAccounts')]
    private User $user;
    #[ORM\Column(name: 'balance', type: 'float')]
    private float $balance;
    #[ORM\Column(name: 'deleted_at', type: 'datetime', nullable: true)]
    private \DateTime $deletedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function getDeletedAt(): \DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(): void
    {
        $this->deletedAt = new \DateTime();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user->getId(),
            'balance' => $this->balance,
        ];
    }
}
