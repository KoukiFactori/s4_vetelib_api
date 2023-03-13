<?php

namespace App\Tests\Api\Espece;

use App\Factory\EspeceFactory;
use App\Tests\Support\ApiTester;

class PutEspeceCest
{
    public function anonymousUserCannotPutEspece(ApiTester $I): void
    {
        EspeceFactory::createOne();
        $I->sendPUT('/api/especes/1', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(401);
    }

}
