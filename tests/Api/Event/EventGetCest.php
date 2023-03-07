<?php

namespace App\Tests\Api\Event;

use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Factory\EventFactory;
use App\Factory\TypeEventFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class EventGetCest
{
    private function InitialiseData()
    {
        $veterinaire = VeterinaireFactory::createOne();
        $type = TypeEventFactory::createOne();
        $client = ClientFactory::createOne();
        $espece = EspeceFactory::createOne();
        $animal = AnimalFactory::createOne(
            ['espece' => $espece,
                'client' => $client,
            ]
        );
        $event = EventFactory::createOne(
            ['typeEvent' => $type,
                'date' => new \DateTime('2021-01-01'),
                'veterinaire' => $veterinaire,
                'animal' => $animal,
            ]
        );
    }

    public function anonymousUserCannotGetEvent(ApiTester $I): void
    {
        $this->InitialiseData();
        $I->sendGET('/api/events/1');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function authenticatedVeterinaireCantGetOtherVeterinaireEvent(ApiTester $I): void
    {
        $veterinaire = VeterinaireFactory::createOne();
        $I->amLoggedInAs($veterinaire->object());
        $this->InitialiseData();
        $I->sendGET('/api/veterinaires/2/events');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function AuthenticatedVeterinaireCanGetHisEvent(ApiTester $I): void
    {
        $veterinaire = VeterinaireFactory::createOne();
        $I->amLoggedInAs($veterinaire->object());
        $type = TypeEventFactory::createOne();
        $client = ClientFactory::createOne();
        $espece = EspeceFactory::createOne();
        $animal = AnimalFactory::createOne(
            ['espece' => $espece,
                'client' => $client,
            ]
        );
        $event = EventFactory::createOne(
            ['typeEvent' => $type,
                'date' => new \DateTime('2021-01-01'),
                'veterinaire' => $veterinaire,
                'animal' => $animal,
            ]
        );
        $I->sendGET('/api/veterinaires/1/events');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function AuthenticatedClientCantGetOtherClientEvent(ApiTester $I): void
    {
        $this->InitialiseData();
        $user = ClientFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->sendGET('/api/clients/2/events');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function AuthenticatedClientCanGetHisEvent(ApiTester $I): void
    {
        $user = ClientFactory::createOne();
        $veterinaire = VeterinaireFactory::createOne();
        $I->amLoggedInAs($user->object());
        $type = TypeEventFactory::createOne();
        $client = ClientFactory::createOne();
        $espece = EspeceFactory::createOne();
        $animal = AnimalFactory::createOne(
            ['espece' => $espece,
                'client' => $client,
            ]
        );
        $event = EventFactory::createOne(
            ['typeEvent' => $type,
                'date' => new \DateTime('2021-01-01'),
                'veterinaire' => $veterinaire,
                'animal' => $animal,
            ]
        );
        $I->sendGET('/api/clients/1/events');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}
