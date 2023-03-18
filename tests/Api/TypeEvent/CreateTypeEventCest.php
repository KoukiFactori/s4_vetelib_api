<?php

namespace App\Tests\Api\TypeEvent;
use App\Factory\AdminFactory;
use App\Factory\ClientFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

Class CreateTypeEventCest
{
    public function anonymousUserCannotCreateTypeEvent(ApiTester $I): void
    {
        $I->sendPOST('/api/type_events', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function authenticatedAdminCanCreateTypeEvent(ApiTester $I): void
    {
        $user = AdminFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/type_events', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(HttpCode::CREATED);
    }

    public function authenticatedClientCannotCreateTypeEvent(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/type_events', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function authenticatedVeterinaireCannotCreateTypeEvent(ApiTester $I): void
    {
        $user = VeterinaireFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/type_events', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

}