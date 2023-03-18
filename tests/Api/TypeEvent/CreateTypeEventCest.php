<?php

namespace App\Tests\Api\TypeEvent;
use App\Factory\AdminFactory;
use App\Factory\ClientFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;

Class CreateTypeEventCest
{
    public function anonymousUserCannotCreateTypeEvent(ApiTester $I): void
    {
        $I->sendPOST('/api/type_events', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(401);
    }

    public function authenticatedAdminCanCreateTypeEvent(ApiTester $I): void
    {
        $user = AdminFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/type_events', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(201);
    }

    public function authenticatedClientCannotCreateTypeEvent(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/type_events', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function authenticatedVeterinaireCannotCreateTypeEvent(ApiTester $I): void
    {
        $user = VeterinaireFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/type_events', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs();
    }

}