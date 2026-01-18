<?php

namespace App\Controller\Web\CreateBuyOrder\v1\Output;

use App\Controller\DTO\OutputDTOInterface;

class CreatedBuyOderDTO implements OutputDTOInterface
{
    public function __construct(
        public readonly array $data
    )
    {
    }
}
