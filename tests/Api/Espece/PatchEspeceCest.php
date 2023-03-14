<?php

namespace App\Tests\Api\Espece;

use App\Factory\AdminFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Factory\VeterinaireFactory;
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

    public function authenticatedAdminCanPatchEspece(ApiTester $I): void
    {
        $user = AdminFactory::createOne();
        EspeceFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPATCH('/api/especes/1', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(200);
    }

    public function authenticatedClientCannotPatchEspece(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        EspeceFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPATCH('/api/especes/1', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function authenticatedVeterinaireCannotPatchEspece(ApiTester $I): void
    {
        $user = VeterinaireFactory::createOne();
        EspeceFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPATCH('/api/especes/1', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(403);
    }

}
