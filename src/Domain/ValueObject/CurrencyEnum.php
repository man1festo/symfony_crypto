<?php

namespace App\Domain\ValueObject;

enum CurrencyEnum: string
{
    case Dollar = 'dollar';
    case Ruble = 'ruble';
    case Bitcoin = 'bitcoin';
    case Ethereum = 'ethereum';
}
