<?php

namespace App\Tests\Api\Espece;

use App\Factory\AdminFactory;
use App\Tests\Support\ApiTester;

class CreateEspeceCest
{
    public function anonymousUserCannotCreateEspece(ApiTester $I): void
    {
        $I->sendPOST('/api/especes', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(401);
    }

    public function authenticatedAdminCanCreateEspece(ApiTester $I): void
    {
        $user = AdminFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/especes', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(201);
    }

}
