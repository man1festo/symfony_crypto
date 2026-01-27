<?php

namespace FeedBundle\Infrastructure\Repository;

use FeedBundle\Domain\Entity\Feed;
use FeedBundle\Domain\Model\CommentModel;

class FeedRepository extends AbstractRepository
{
    public function putCommentToUserFeed(CommentModel $comment, int $userId): bool
    {
        $feed = $this->ensureFeedForUser($userId);
        if ($feed === null) {
            return false;
        }
        $comments = $feed->getComments();
        $comments[] = $comment->toFeed();
        $feed->setComments($comments);
        $this->flush();

        return true;
    }

    public function ensureFeedForUser(int $userId): ?Feed
    {
        $feedRepository = $this->entityManager->getRepository(Feed::class);
        $feed = $feedRepository->findOneBy(['userId' => $userId]);
        if (!($feed instanceof Feed)) {
            $feed = new Feed();
            $feed->setUserId($userId);
            $feed->setComments([]);
            $this->store($feed);
        }

        return $feed;
    }

}
