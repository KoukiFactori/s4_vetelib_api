<?php

namespace App\Tests\Api\Espece;

use App\Factory\AdminFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class DeleteEspeceCest
{
    public function anonymousUserCannotDeleteEspece(ApiTester $I): void
    {
        EspeceFactory::createOne();
        $I->sendDELETE('/api/especes/1');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function authenticatedAdminCanDeleteEspece(ApiTester $I): void
    {
        $user = AdminFactory::createOne();
        EspeceFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendDELETE('/api/especes/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function authenticatedClientCannotDeleteEspece(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        EspeceFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendDELETE('/api/especes/1');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function authenticatedVeterinaireCannotDeleteEspece(ApiTester $I): void
    {
        $user = VeterinaireFactory::createOne();
        EspeceFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendDELETE('/api/especes/1');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

}
