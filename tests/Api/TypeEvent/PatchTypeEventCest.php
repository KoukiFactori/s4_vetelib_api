<?php

namespace App\Tests\Api\TypeEvent;
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
}