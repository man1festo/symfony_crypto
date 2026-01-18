<?php

namespace App\Controller\Web\CreateAccount\v1\Input;

use Symfony\Component\Validator\Constraints as Assert;

class CreateAccountDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly string $type
    )
    {
    }
}
