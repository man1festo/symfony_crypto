<?php

namespace App\Controller\Web\CreateUser\v2\Output;

use App\Controller\DTO\OutputDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreatedUserDTO implements OutputDTOInterface
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank()]
        public readonly string $login,
        #[Assert\Type('integer')]
        public readonly int $id,
    )
    {

    }
}
