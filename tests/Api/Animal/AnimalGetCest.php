<?php

namespace App\Tests\Api\Animal;

use App\Factory\AdminFactory;
use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class AnimalGetCest
{
    public function anonymousCannotGetAnimal(ApiTester $I): void
    {
        AnimalFactory::createOne(['name' => 'Rex', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => ClientFactory::createOne()]);
        $I->sendGET('/api/animals/1');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function authenticatedClientCanGetHisAnimal(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        AnimalFactory::createOne(['name' => 'Rex', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => $user]);
        $I->amLoggedInAs($user->object());
        $I->sendGet('/api/animals/1');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function authenticatedClientCannotGetOtherAnimal(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        AnimalFactory::createOne(['name' => 'Rex', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => ClientFactory::createOne()]);
        $I->amLoggedInAs($user->object());
        $I->sendGet('/api/animals/1');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function authenticatedVeterinaireCanGetAnimal(ApiTester $I): void
    {
        AnimalFactory::createOne(['name' => 'Rex', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => ClientFactory::createOne()]);
        $user = VeterinaireFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendGet('/api/animals/1');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function authenticatedAdminCanGetAnimal(ApiTester $I): void
    {
        AnimalFactory::createOne(['name' => 'Rex', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => ClientFactory::createOne()]);
        $user = AdminFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendGet('/api/animals/1');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
    
}
