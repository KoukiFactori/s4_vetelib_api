<?php

namespace App\Tests\Api\Espece;

use App\Factory\AdminFactory;
use App\Factory\ClientFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class CreateEspeceCest
{
    public function anonymousUserCannotCreateEspece(ApiTester $I): void
    {
        $I->sendPOST('/api/especes', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function authenticatedAdminCanCreateEspece(ApiTester $I): void
    {
        $user = AdminFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/especes', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(HTTPCode::CREATED);
    }

    public function authenticatedClientCannotCreateEspece(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/especes', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeiS(HttpCode::FORBIDDEN);
    }

    public function authenticatedVeterinaireCannotCreateEspece(ApiTester $I): void
    {
        $user = VeterinaireFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/especes', [
            'name' => 'test',
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

}
