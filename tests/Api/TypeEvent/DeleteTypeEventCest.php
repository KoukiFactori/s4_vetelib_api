<?php

namespace App\Tests\Api\TypeEvent;
use App\Factory\AdminFactory;
use App\Factory\ClientFactory;
use App\Factory\TypeEventFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;


class DeleteTypeEventCest
{
    public function anonymousUserCannotDeleteTypeEvent(ApiTester $I): void
    {
        TypeEventFactory::createOne();
        $I->sendDELETE('/api/typeEvents/1');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
    public function authenticatedAdminCanDeleteTypeEvent(ApiTester $I): void
    {
        $user = AdminFactory::createOne();
        TypeEventFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendDELETE('/api/typeEvents/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function authenticatedClientCannotDeleteTypeEvent(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        TypeEventFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendDELETE('/api/typeEvents/1');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function authenticatedVeterinaireCannotDeleteTypeEvent(ApiTester $I): void
    {
        $user = VeterinaireFactory::createOne();
        TypeEventFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendDELETE('/api/typeEvents/1');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}