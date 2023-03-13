<?php

namespace App\Tests\Api\Espece;

use App\Factory\EspeceFactory;
use App\Tests\Support\ApiTester;

class PatchEspeceCest
{
    public function anonymousUserCannotPatchEspece(ApiTester $I): void
    {
        EspeceFactory::createOne();
        $I->sendPATCH('/api/especes/1', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(401);
    }

}
