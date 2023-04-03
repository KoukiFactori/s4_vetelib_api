<?php

namespace App\Tests\Api\Animal;

use App\Factory\AdminFactory;
use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class AnimalPatchCest
{
    public function anonymousCannotPatchAnimal(ApiTester $I): void
    {
        AnimalFactory::createOne(['name' => 'Matoufeu', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => ClientFactory::createOne()]);
        $I->sendPATCH('/api/animals/1', [
            'name' => 'Donald',
            'espece' => '/api/especes/1',
            'client' => '/api/clients/1',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function authenticatedVeterinaireCannotPatchAnimal(ApiTester $I): void
    {
        $user = VeterinaireFactory::createOne();
        $I->amLoggedInAs($user->object());
        AnimalFactory::createOne(['name' => 'Matoufeu', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => ClientFactory::createOne()]);
        $I->sendPATCH('/api/animals/1', [
            'name' => 'Donald',
            'espece' => '/api/especes/1',
            'client' => '/api/clients/1',
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function authenticatedAdminCanPatchAnimal(ApiTester $I): void
    {
        $user = AdminFactory::createOne();
        $I->amLoggedInAs($user->object());
        EspeceFactory::createOne();
        ClientFactory::createOne();
        AnimalFactory::createOne(['name' => 'Matoufeu', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => ClientFactory::createOne()]);
        $I->sendPATCH('/api/animals/1', [
            'name' => 'Donald',
            'espece' => '/api/especes/1',
            'client' => '/api/clients/2',
            'birthdate' => '2021-01-01',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function authenticatedClientCanPatchHisAnimal(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        AnimalFactory::createOne(['name' => 'Matoufeu', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => $user]);
        $I->amLoggedInAs($user->object());
        $I->sendPATCH('/api/animals/1', [
            'name' => 'Donald',
            'espece' => '/api/especes/1',
            'client' => '/api/clients/1',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function authenticatedClientCannotPatchOtherClientAnimal(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $I->amLoggedInAs($user->object());
        AnimalFactory::createOne(['name' => 'Matoufeu', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => ClientFactory::createOne()]);
        $I->sendPATCH('/api/animals/1', [
            'name' => 'Donald',
            'espece' => '/api/especes/1',
            'client' => '/api/clients/2',
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function authenticatedClientCannotPatchDataClientAnimal(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $I->amLoggedInAs($user->object());
        AnimalFactory::createOne(['name' => 'Matoufeu', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => ClientFactory::createOne()]);
        $I->sendPATCH('/api/animals/1', [
            'name' => 'Donald',
            'espece' => '/api/especes/1',
            'client' => '/api/clients/2',
        ]);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}
