<?php

namespace App\Tests\Api\TypeEvent;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Factory\AdminFactory;
use App\Factory\ClientFactory;
use App\Factory\VeterinaireFactory;
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
        $I->amLoggedIn(AdminFactory::createOne()->object());
        $I->sendGET('/api/type_events');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
    public function authenticatedClientCanGetTypeEvent(ApiTester $I): void
    {
        $I->amLoggedInAsClient(ClientFactory::createOne()->object());
        $I->sendGET('/api/type_events');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
    public function authenticatedVeterinaireCanGetTypeEvent(ApiTester $I): void
    {
        $I->amLoggedIn(VeterinaireFactory::createOne()->object());
        $I->sendGET('/api/type_events');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function anonymoususerCannotGetOneTypeEvent(ApiTester $I): void
    {
        $I->sendGET('/api/type_events/1');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
    public function authenticatedAdminCanGetOneTypeEvent(ApiTester $I): void
    {
        $I->amLoggedIn(AdminFactory::createOne()->object());
        $I->sendGET('/api/type_events/1');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function authenticatedClientCanGetOneTypeEvent(ApiTester $I): void
    {
        $I->amLoggedInAsClient(ClientFactory::createOne()->object());
        $I->sendGET('/api/type_events/1');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}

