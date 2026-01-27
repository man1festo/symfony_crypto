<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;

interface FeedRepositoryInterface
{
    public function getFeed(int $userId): array;
}
