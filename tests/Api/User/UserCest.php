<?php

namespace App\Tests\Api\User;

use App\Entity\Admin;
use App\Entity\Client;
use App\Entity\Veterinaire;
use App\Factory\AdminFactory;
use App\Factory\ClientFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class UserCest
{
    public function anonymousShouldNotGetUser(ApiTester $tester)
    {
        $tester->sendGet('/api/me');
        $tester->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function userShouldGetOwnInformations(ApiTester $tester)
    {
        $data = [
          'lastname' => 'thanlime',
          'firstname' => 'faster',
          'email' => 'fasterthanlime@dev',
          'city' => 'Reims',
          'zipcode' => 51100,
          'address' => 'Somewhere',
        ];

        $user = ClientFactory::createOne($data);
        $tester->amLoggedInAs($user->object());

        $tester->sendGet('/api/me');
        $tester->seeResponseCodeIs(HttpCode::OK);
        $tester->seeResponseIsJson();
        $tester->seeResponseIsAnEntity(Client::class, '/api/clients/1');
    }

    public function clientShouldGetClientHydraMetadata(ApiTester $tester)
    {
        $user = ClientFactory::createOne();
        $tester->amLoggedInAs($user->object());

        $tester->sendGet('/api/me');
        $tester->seeResponseCodeIs(HttpCode::OK);
        $tester->seeResponseIsJson();
        $tester->seeResponseIsAnEntity(Client::class, '/api/clients/1');
    }

    public function veterinaireShouldGetVeterinaireHydraMetadata(ApiTester $tester)
    {
        $user = VeterinaireFactory::createOne();
        $tester->amLoggedInAs($user->object());

        $tester->sendGet('/api/me');
        $tester->seeResponseCodeIs(HttpCode::OK);
        $tester->seeResponseIsJson();
        $tester->seeResponseIsAnEntity(Veterinaire::class, '/api/veterinaires/1');
    }

    public function adminShouldGetAdminHydraMetadata(ApiTester $tester)
    {
        $user = AdminFactory::createOne();
        $tester->amLoggedInAs($user->object());

        $tester->sendGet('/api/me');
        $tester->seeResponseCodeIs(HttpCode::OK);
        $tester->seeResponseIsJson();
        $tester->seeResponseIsAnEntity(Admin::class, '/api/admins/1');
    }
}
