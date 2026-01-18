<?php

namespace App\Controller\Web\CreateAccount\v1\Output;

use App\Controller\DTO\OutputDTOInterface;
use App\Domain\ValueObject\CurrencyEnum;

class CreatedAccountDTO implements OutputDTOInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $currency,
        public readonly float $balance,
    )
    {

    }
}
