<?php

namespace App\Controller\Web\CreateSellOrder\v1\Output;

use App\Controller\DTO\OutputDTOInterface;

class CreatedSellOrderDTO implements OutputDTOInterface
{
    public function __construct(
        public readonly array $data
    )
    {

    }
}
