<?php

namespace App\Controller\Web\CreateUser\v2\Input;

use Symfony\Component\Validator\Constraints as Assert;


class CreateUserDTO
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank()]
        public readonly string $login,
        #[Assert\Type('string')]
        #[Assert\NotBlank()]
        public readonly string $password,
    )
    {

    }
}
