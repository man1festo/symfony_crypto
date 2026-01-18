<?php

namespace App\Controller\Web\DepositIntoAccount\v1\Output;


use App\Controller\DTO\OutputDTOInterface;

class DepositedIntoAccountDTO implements OutputDTOInterface
{
    public function __construct(
        public readonly array $data,
    )
    {

    }
}
