<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Comment;

class CommentRepository extends AbstractRepository
{
    public function create(Comment $comment): int
    {
        return $this->store($comment);
    }

    public function getByOrder(int $orderId): array
    {
        return $this->entityManager->getRepository(Comment::class)->findBy(['order' => 1]);
    }
}
