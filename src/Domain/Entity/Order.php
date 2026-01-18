<?php

namespace App\Domain\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`order`')]
#[ORM\Index(name: 'order__seller_id__ind', columns: ['seller_id'])]
#[ORM\Index(name: 'order__seller_account_id__ind', columns: ['seller_account_id'])]
#[ORM\Index(name: 'order__buyer_id__ind', columns: ['buyer_id'])]
#[ORM\Index(name: 'order__buyer_account_id__ind', columns: ['buyer_account_id'])]
#[ORM\Index(name: 'order__amount_to_sell__ind', columns: ['amount_to_sell'])]
#[ORM\Index(name: 'order__amount_to_buy__ind', columns: ['amount_to_buy'])]
class Order implements EntityInterface, SoftDeletableInterface
{
    #[ORM\Column(name: 'id', type: 'integer', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private ?DateTime $createdAt;
    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private ?DateTime $updatedAt;
    #[ORM\Column(name: 'deleted_at', type: 'datetime', nullable: true)]
    private DateTime $deletedAt;
    #[ORM\ManyToOne(targetEntity: 'User', inversedBy: 'saleOrders')]
    private ?User $seller = null;
    #[ORM\ManyToOne(targetEntity: 'Account')]
    private ?Account $sellerAccount = null;
    #[ORM\ManyToOne(targetEntity: 'User', inversedBy: 'purchaseOrders')]
    private ?User $buyer = null;
    #[ORM\ManyToOne(targetEntity: 'Account')]
    private ?Account $buyerAccount = null;
    #[ORM\Column(name: 'amount_to_sell', type: 'bigint', nullable: true)]
    private ?int $amountToSell = null;
    #[ORM\Column(name: 'amount_to_buy', type: 'bigint', nullable: true)]
    private ?int $amountToBuy = null;
    #[ORM\Column(name: 'seller_approve', type: 'boolean', nullable: true)]
    private ?bool $sellerApprove = null;
    #[ORM\Column(name: 'buyer_approve', type: 'boolean', nullable: true)]
    private ?bool $buyerApprove = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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

    public function getSeller(): ?User
    {
        return $this->seller;
    }

    public function setSeller(?User $seller): void
    {
        $this->seller = $seller;
    }

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): void
    {
        $this->buyer = $buyer;
    }

    public function getBuyerAccount(): ?Account
    {
        return $this->buyerAccount;
    }

    public function setBuyerAccount(?Account $buyerAccount): void
    {
        $this->buyerAccount = $buyerAccount;
    }

    public function getSellerAccount(): ?Account
    {
        return $this->sellerAccount;
    }

    public function setSellerAccount(?Account $sellerAccount): void
    {
        $this->sellerAccount = $sellerAccount;
    }

    public function getAmountToSell(): ?int
    {
        return $this->amountToSell;
    }

    public function setAmountToSell(?int $amountToSell): void
    {
        $this->amountToSell = $amountToSell;
    }

    public function getAmountToBuy(): ?int
    {
        return $this->amountToBuy;
    }

    public function setAmountToBuy(?int $amountToBuy): void
    {
        $this->amountToBuy = $amountToBuy;
    }

    public function getDeletedAt(): DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(): void
    {
        $this->deletedAt = new DateTime();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'seller' => ($this->seller) ? $this->seller->getId() : null,
            'sellerAccount' => ($this->sellerAccount) ? $this->sellerAccount->getId() : null,
            'buyer' => ($this->buyer) ? $this->buyer->getId() : null,
            'buyerAccount' => ($this->buyerAccount) ? $this->buyerAccount->getId() : null,
            'amountToSell' => $this->amountToSell,
            'amountToBuy' => $this->amountToBuy,
        ];
    }

    public function getSellerApprove(): ?bool
    {
        return $this->sellerApprove;
    }

    public function setSellerApprove(?bool $sellerApprove): void
    {
        $this->sellerApprove = $sellerApprove;
    }

    public function getBuyerApprove(): ?bool
    {
        return $this->buyerApprove;
    }

    public function setBuyerApprove(?bool $buyerApprove): void
    {
        $this->buyerApprove = $buyerApprove;
    }

}
