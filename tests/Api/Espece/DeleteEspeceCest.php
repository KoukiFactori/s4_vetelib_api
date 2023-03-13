<?php

namespace App\Tests\Api\Espece;

use App\Factory\AdminFactory;
use App\Factory\EspeceFactory;
use App\Tests\Support\ApiTester;

class DeleteEspeceCest
{
    public function anonymousUserCannotDeleteEspece(ApiTester $I): void
    {
        EspeceFactory::createOne();
        $I->sendDELETE('/api/especes/1');
        $I->seeResponseCodeIs(401);
    }

    public function authenticatedAdminCanDeleteEspece(ApiTester $I): void
    {
        $user = AdminFactory::createOne();
        EspeceFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendDELETE('/api/especes/1');
        $I->seeResponseCodeIs(204);
    }
}
