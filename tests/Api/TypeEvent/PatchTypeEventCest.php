<?php

namespace App\Tests\Api\TypeEvent;
use App\Factory\AdminFactory;
use App\Factory\TypeEventFactory;
use App\Tests\Support\ApiTester;

class PatchTypeEventCest
{
    public function anonymousUserCannotPatchTypeEvent(ApiTester $I): void
    {
        TypeEventFactory::createOne();
        $I->sendPATCH('/api/typeEvents/1', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(401);
    }
    public function authenticatedAdminCanPatchTypeEvent(ApiTester $I): void
    {
        $user = AdminFactory::createOne();
        TypeEventFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPATCH('/api/typeEvents/1', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(200);
    }
}