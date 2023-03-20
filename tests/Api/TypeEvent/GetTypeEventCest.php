<?php

namespace App\Tests\Api\TypeEvent;

use App\Factory\AdminFactory;
use App\Factory\ClientFactory;
use App\Factory\EventFactory;
use App\Factory\TypeEventFactory;
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
    {   TypeEventFactory::createOne();
        $I->sendGET('/api/typeEvents/1');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
    public function authenticatedAdminCanGetOneTypeEvent(ApiTester $I): void
    {   TypeEventFactory::createOne();
        $user=AdminFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendGET('/api/typeEvents/1');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function authenticatedClientCanGetOneTypeEvent(ApiTester $I): void
    {   TypeEventFactory::createOne();
        $user=ClientFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendGET('/api/typeEvents/1');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
    public function authenticatedVeterinaireCanGetOneTypeEvent(ApiTester $I): void
    {   TypeEventFactory::createOne();
        $user=VeterinaireFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendGET('/api/typeEvents/1');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}

