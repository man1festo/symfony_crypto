<?php

namespace App\Controller\Web\UserForm\v1\Input;

use Symfony\Component\Validator\Constraints as Assert;
class UserFormDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $login = null,
        #[Assert\Length(min: 5, max: 12)]
        public ?string $password = '',
        public ?int $id = null
    )
    {

    }
}
