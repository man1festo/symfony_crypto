<?php

namespace FeedBundle\Domain\Entity;

use FeedBundle\Domain\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Shared\Domain\Model\OId;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class Feed implements EntityInterface
{
    #[ORM\Column(name: 'id', type: 'bigint', unique:true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column]
    private int $userId;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $comments;
    #[ORM\Column(type: UuidType::NAME)]
    private Uuid $uid;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getComments(): ?array
    {
        return $this->comments;
    }

    public function setComments(?array $comments): void
    {
        $this->comments = $comments;
    }

}
