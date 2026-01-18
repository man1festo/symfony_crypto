<?php

namespace Support\Helper;

use App\Domain\Entity\Account;
use App\Domain\Entity\DollarAccount;
use App\Domain\Entity\User;
use App\Domain\ValueObject\CurrencyEnum;
use Codeception\Module;
use Codeception\Module\DataFactory;
use League\FactoryMuffin\Faker\Facade;

class Factories extends Module
{
    public function _beforeSuite($settings = []): void
    {
        /** @var DataFactory $factory */
        $factory = $this->getModule('DataFactory');

        $factory->_define(
            User::class,
            [
                'login' => Facade::text(20),
                'password' => Facade::text(20),
                'roles' => [],
            ]
        );
        $factory->_define(
            DollarAccount::class,
            [
                'balance' => Facade::randomNumber(3),
            ]
        );
    }
}
