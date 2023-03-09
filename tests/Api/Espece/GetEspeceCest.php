<?php

namespace App\Tests\Api\Espece;

use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Tests\Support\ApiTester;

class GetEspeceCest
{
    public function authenticatedClientCanGetEspece(ApiTester $I): void
    {
        EspeceFactory::createOne();
        $user = ClientFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendGET('/api/especes/1');
        $I->seeResponseCodeIs(200);
    }
}
