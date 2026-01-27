<?php

namespace App\Controller\Web\GetFeed\v1\Output;


class Response
{
    /**
     * @param TweetDTO[] $tweets
     */
    public function __construct(
        public array $tweets,
    ) {
    }
}
