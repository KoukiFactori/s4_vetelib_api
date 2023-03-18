<?php

namespace App\Tests\Api\TypeEvent;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class GetTypeEventCest 
{
    public function anonymousUserCannotGetTypeEvent(ApiTester $I): void
    {
        $I->sendGET('/api/type_events');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
    public function authenticatedAdminCanGetTypeEvent(ApiTester $I): void
    {
        $I->amLoggedInAsAdmin();
        $I->sendGET('/api/type_events');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}

