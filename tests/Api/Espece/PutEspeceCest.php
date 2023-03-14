<?php

namespace App\Tests\Api\Espece;

use App\Factory\AdminFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Factory\VeterinaireFactory;
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

    public function authenticatedAdminCanPutEspece(ApiTester $I): void
    {
        $user = AdminFactory::createOne();
        EspeceFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPUT('/api/especes/1', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(200);
    }

    public function authenticatedClientCannotPutEspece(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        EspeceFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPUT('/api/especes/1', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function authenticatedVeterinaireCannotPutEspece(ApiTester $I): void
    {
        $user = VeterinaireFactory::createOne();
        EspeceFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPUT('/api/especes/1', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(403);
    }

}
