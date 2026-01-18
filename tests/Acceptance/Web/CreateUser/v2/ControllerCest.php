<?php

namespace AcceptanceTests\Web\CreateUser\v2;

use App\Tests\Support\AcceptanceTester;
use Codeception\Util\HttpCode;

class ControllerCest
{
    public function testAddUserActionAdmin(AcceptanceTester $I): void
    {
        $I->amAdmin();
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/api/v2/user/create', [
            'login' => 'new_user',
            'password' => 'secure_password',
        ]);
        $I->canSeeResponseCodeIs(HttpCode::OK);
        $I->canSeeResponseMatchesJsonType(['id' => 'integer:>0']);
    }

    public function testAddUserActionUser(AcceptanceTester $I): void
    {
        $I->amUser();
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/api/v2/user/create', [
            'login' => 'new_user',
            'password' => 'secure_password',
        ]);
        $I->canSeeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}
