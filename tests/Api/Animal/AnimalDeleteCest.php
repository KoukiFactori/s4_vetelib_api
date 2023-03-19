<?php

namespace App\Tests\Api\Animal;
use App\Factory\AdminFactory;
use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class AnimalDeleteCest
{
    public function anonymousCannotDeleteAnimal(ApiTester $I): void
    {
        AnimalFactory::createOne(['name' => 'Matoufeu', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => ClientFactory::createOne()]);
        $I->sendDELETE('/api/animals/1');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function authenticatedVeterinaireCannotDeleteAnimal(ApiTester $I): void
    {
        $user = VeterinaireFactory::createOne();
        $I->amLoggedInAs($user->object());
        AnimalFactory::createOne(['name' => 'Matoufeu', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => ClientFactory::createOne()]);
        $I->sendDELETE('/api/animals/1');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function authenticatedAdminCanDeleteAnimal(ApiTester $I): void
    {
        $user = AdminFactory::createOne();
        $I->amLoggedInAs($user->object());
        AnimalFactory::createOne(['name' => 'Matoufeu', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => ClientFactory::createOne()]);
        $I->sendDELETE('/api/animals/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function authenticatedClientCanDeleteHisAnimal(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        AnimalFactory::createOne(['name' => 'Matoufeu', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => $user]);
        $I->amLoggedInAs($user->object());
        $I->sendDELETE('/api/animals/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function authenticatedClientCannotDeleteOtherAnimal(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        AnimalFactory::createOne(['name' => 'Matoufeu', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => ClientFactory::createOne()]);
        $I->amLoggedInAs($user->object());
        $I->sendDELETE('/api/animals/1');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}