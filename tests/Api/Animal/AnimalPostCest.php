<?php

namespace App\Tests\Api\Animal;
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
}