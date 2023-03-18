<?php

namespace App\Tests\Api\TypeEvent;
use App\Factory\AdminFactory;
use App\Factory\ClientFactory;
use App\Factory\TypeEventFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class PatchTypeEventCest
{
    public function anonymousUserCannotPatchTypeEvent(ApiTester $I): void
    {
        TypeEventFactory::createOne();
        $I->sendPATCH('/api/typeEvents/1', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
    public function authenticatedAdminCanPatchTypeEvent(ApiTester $I): void
    {
        $user = AdminFactory::createOne();
        TypeEventFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPATCH('/api/typeEvents/1', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function authenticatedClientCannotPatchTypeEvent(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        TypeEventFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPATCH('/api/typeEvents/1', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}