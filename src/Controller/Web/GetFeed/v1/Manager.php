<?php

namespace App\Controller\Web\GetFeed\v1;

use App\Controller\Web\GetFeed\v1\Output\Response;
use App\Controller\Web\GetFeed\v1\Output\TweetDTO;
use App\Domain\Entity\User;
use FeedBundle\Domain\Services\FeedService;

class Manager
{
    public function __construct(private readonly FeedService $feedService)
    {
    }

    public function getFeed(User $user): Response
    {
        return new Response(
            array_map(
                static fn (array $tweetData): TweetDTO => new TweetDTO(...$tweetData),
                $this->feedService->getFeed($user->getId()),
            )
        );
    }
}
