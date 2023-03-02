<?php

namespace App\Tests\Api\Event;

use App\Entity\Event;
use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Factory\EventFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class UserGetMeCest
{
    private function InitialiseData(){
        $veterinaire = UserFactory::createOne();
        $client = UserFactory::createOne();
        $espece=EspeceFactory::createOne();
        $animal = AnimalFactory::createOne(
            [   'espece' => $espece,
                'client' => $client
            ]
        );
        $event = EventFactory::createOne(
            [
                'veterinaire' => $veterinaire,
                'animal' => $animal
            ]
        );

    }
    public function anonymousUserCannotGetEvent(ApiTester $I): void
    {   
        AnimalFactory::createOne();
        ClientFactory::createOne();
        EventFactory::createOne();
        $I->sendGET('/api/events/1');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function authenticatedVeterinaireCantGetOtherVeterinaireEvent(ApiTester $I): void
    {
        $veterinaire = UserFactory::createOne();
        $I->amAuthenticatedAs($veterinaire->object());
        $veterinaire2 = UserFactory::createOne();
        EventFactory::createOne(
            [
                'veterinaire' => $veterinaire2
            ]
        );
        $I->sendGET('/api/veterinaires/2/events');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
    public function AuthenticatedVeterinaireCanGetHisEvent(ApiTester $I): void
    {
        $veterinaire = UserFactory::createOne();
        $I->amAuthenticatedAs($veterinaire->object());
        EventFactory::createOne(
            [
                'veterinaire' => $veterinaire
            ]
        );
        $I->sendGET('/api/veterinaires/1/events');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function AuthenticatedClientCantGetOtherClientEvent(ApiTester $I): void
    {
        $user = UserFactory::createOne();
        $I->amAuthenticatedAs($user->object());
        $user2 = UserFactory::createOne();
        AnimalFactory::createOne(
            [
                'client' =>$user2
            ]
        );
        EventFactory::createOne(
            [
                'animal' => '/api/animals/1'
            ]
        );
        $I->sendGET('/api/clients/2/events');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
    public function AuthenticatedClientCanGetHisEvent(ApiTester $I): void
    {
        $user = UserFactory::createOne();
        $I->amAuthenticatedAs($user->object());
        $animal = AnimalFactory::createOne(
            [
                'client' =>$user
            ]
        );
        EventFactory::createOne(
            [
                'animal' => $animal
            ]
        );
        $I->sendGET('/api/clients/1/events');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

}
