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


}