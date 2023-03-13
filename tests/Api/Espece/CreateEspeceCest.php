<?php

namespace App\Tests\Api\Espece;

use App\Factory\AdminFactory;
use App\Factory\ClientFactory;
use App\Factory\VeterinaireFactory;
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

    public function authenticatedClientCannotCreateEspece(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/especes', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function authenticatedVeterinaireCannotCreateEspece(ApiTester $I): void
    {
        $user = VeterinaireFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/especes', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(403);
    }

}
