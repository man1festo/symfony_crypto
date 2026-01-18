<?php

namespace App\Domain\Event;

class CreateUserEvent
{
    public ?int $id = null;

    public function __construct(
        public readonly string $login,
    ) {
    }
}
