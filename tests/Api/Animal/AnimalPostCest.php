<?php

namespace App\Tests\Api\Animal;

use App\Factory\AdminFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class AnimalPostCest
{
    public function anonymousCannotPostAnimal(ApiTester $I): void
    {
        $I->sendPOST('/api/animals', [
            'name' => 'Donald',
            'espece' => '/api/especes/1',
            'client' => '/api/clients/1',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function authenticatedVeterinaireCannotPostAnimal(ApiTester $I): void
    {
        $user = VeterinaireFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/animals', [
            'name' => 'Donald',
            'espece' => '/api/especes/1',
            'client' => '/api/clients/1',
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function authenticatedAdminCanPostAnimal(ApiTester $I): void
    {
        $user = AdminFactory::createOne();
        $I->amLoggedInAs($user->object());
        EspeceFactory::createOne();
        ClientFactory::createOne();
        $I->sendPOST('/api/animals', [
            'name' => 'Donald',
            'espece' => '/api/especes/1',
            'client' => '/api/clients/2',
            'birthdate' => '2021-01-01',
        ]);
        $I->seeResponseCodeIs(HttpCode::CREATED);
    }

    public function authenticatedClientCanPostAnimalForHim(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        EspeceFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/animals', [
            'name' => 'Donald',
            'espece' => '/api/especes/1',
            'client' => '/api/clients/1',
            'birthdate' => '2021-01-01',
        ]);
        $I->seeResponseCodeIs(HttpCode::CREATED);
    }

    public function authenticatedClientCannotPostAnimalForOtherClient(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        EspeceFactory::createOne();
        ClientFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendPOST('/api/animals', [
            'name' => 'Donald',
            'espece' => '/api/especes/1',
            'client' => '/api/clients/2',
            'birthdate' => '2021-01-01',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }
}
