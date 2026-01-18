<?php

namespace FunctionalTests\Controller\Cli;

use App\Domain\Entity\Account;
use App\Domain\Entity\DollarAccount;
use App\Domain\Entity\User;
use App\Tests\Support\FunctionalTester;
use Codeception\Example;

class CreateOrdersCommandCest
{
    private const COMMAND = 'orders:add';

    public function _before(FunctionalTester $I): void
    {

    }
    /** @dataProvider executeDataProvider */
    public function testExecuteReturnsResult(FunctionalTester $I, Example $example): void
    {
        $userId = $I->have(User::class);
        $accountId = $I->have(DollarAccount::class);
        $params = ['userId' => $userId->getId(), 'accountId' => $accountId->getId(), 'amount' => $example['amount'], 'count' => $example['count']];
        $output = $I->runSymfonyConsoleCommand(self::COMMAND, $params, [], $example['code']);
        $I->assertStringEndsWith($example['expected'], $output);
    }

    private function executeDataProvider(): array
    {
        return [
            'positive' => ['count' => 2, 'amount' => 2, 'expected' => "2 orders created\n", 'code' => 0],
            'zero' => ['count' => 0, 'amount' => 2, 'expected' => "Count should be positive integer\n", 'code' => 1],
            'negative' => ['count' => -1, 'amount' => 2, 'expected' => "Count should be positive integer\n", 'code' => 1]
        ];
    }
}
