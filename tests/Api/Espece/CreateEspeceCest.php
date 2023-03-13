<?php

namespace App\Tests\Api\Espece;

use App\Tests\Support\ApiTester;

class CreateEspeceCest
{
    public function anonymousUserCannotCreateEspece(ApiTester $I): void
    {
        $I->sendPOST('/api/especes', [
            'nom' => 'test',
        ]);
        $I->seeResponseCodeIs(401);
    }
}
