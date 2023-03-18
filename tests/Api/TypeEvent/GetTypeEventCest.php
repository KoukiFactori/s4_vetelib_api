<?php

namespace App\Tests\Api\TypeEvent;

use App\Factory\AdminFactory;
use App\Factory\ClientFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class GetTypeEventCest 
{
    public function anonymousUserCannotGetTypeEvent(ApiTester $I): void
    {
        $I->sendGET('/api/typeEvents');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
    public function authenticatedAdminCanGetTypeEvent(ApiTester $I): void
    {
        $I->amLoggedInAs(AdminFactory::createOne()->object());
        $I->sendGET('/api/typeEvents');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
    public function authenticatedClientCanGetTypeEvent(ApiTester $I): void
    {
        $I->amLoggedInAs(ClientFactory::createOne()->object());
        $I->sendGET('/api/typeEvents');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
    public function authenticatedVeterinaireCanGetTypeEvent(ApiTester $I): void
    {
        $I->amLoggedInAs(VeterinaireFactory::createOne()->object());
        $I->sendGET('/api/typeEvents');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function anonymoususerCannotGetOneTypeEvent(ApiTester $I): void
    {
        $I->sendGET('/api/typeEvents/1');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
    public function authenticatedAdminCanGetOneTypeEvent(ApiTester $I): void
    {
        $I->amLoggedInAs(AdminFactory::createOne()->object());
        $I->sendGET('/api/typeEvents/1');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function authenticatedClientCanGetOneTypeEvent(ApiTester $I): void
    {
        $I->amLoggedInAs(ClientFactory::createOne()->object());
        $I->sendGET('/api/typeEvents/1');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
    public function authenticatedVeterinaireCanGetOneTypeEvent(ApiTester $I): void
    {
        $I->amLoggedInAs(VeterinaireFactory::createOne()->object());
        $I->sendGET('/api/typeEvents/1');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}

