<?php

namespace FeedBundle\Domain\Services;

use FeedBundle\Domain\Model\CommentModel;
use FeedBundle\Infrastructure\Repository\FeedRepository;

class FeedService
{
    public function __construct(
        private readonly FeedRepository $feedRepository
    )
    {

    }

    public function addComment(CommentModel $commentModel, $userId)
    {
        $this->feedRepository->putCommentToUserFeed($commentModel, $userId);
    }

    public function getFeed(int $userId): array
    {
        $feed = $this->feedRepository->ensureFeedForUser($userId);
        return $feed === null ? [] : $feed->getComments();
    }
}
