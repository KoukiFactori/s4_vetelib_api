<?php

namespace App\Tests\Api\Animal;
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
}