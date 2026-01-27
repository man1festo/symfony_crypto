<?php

namespace FeedBundle\Controller\Web\GetFeed\v1\Output;


class TweetDTO
{
    public function __construct(
        public int $id,
        public string $user,
        public string $text,
        public string $order,
    ) {
    }
}
