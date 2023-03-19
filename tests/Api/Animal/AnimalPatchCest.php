<?php

namespace App\Tests\Api\Animal;
use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class AnimalPatchCest
{
    public function anonymousCannotPatchAnimal(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        AnimalFactory::createOne(['name' => 'Rex', 'espece' => EspeceFactory::createOne(['name' => 'Chien']), 'client' => ClientFactory::createOne()]);
        $I->sendPATCH('/api/animals/1', [
            'name' => 'Donald',
            'espece' => '/api/especes/1',
            'client' => '/api/clients/1',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}