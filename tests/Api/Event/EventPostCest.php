<?php

namespace App\Tests\Api\Event;


use App\Factory\AdminFactory;
use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Factory\EventFactory;
use App\Factory\TypeEventFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EventPostCest
{
    private function InitialiseData()
    {
        $veterinaire = VeterinaireFactory::createOne();
        $type=TypeEventFactory::createOne();
        $client = ClientFactory::createOne();
        $espece=EspeceFactory::createOne();
        $animal = AnimalFactory::createOne(
            [   'espece' => $espece,
                'client' => $client
            ]
        );
        $event = EventFactory::createOne(
            [   'typeEvent' => $type,
                'date' => new \DateTime('2021-01-01'),
                'veterinaire' => $veterinaire,
                'animal' => $animal
            ]
        );

    }
    public function anonymousUserCantPostEvent(ApiTester $I): void{
    $this->InitialiseData();
    $dataInitPost = [
        'id' => 2,
        'date' => '2023-03-11T09:30:00+00:00',
        'description' => 'test1',
        'animal' => '/api/animals/1',
        'typeEvent' => '/api/type_events/1',
        'veterinaire' => '/api/veterinaires/1',
    ];
    $I->sendPOST('/api/events', $dataInitPost);
    $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
}
    public function authenticatedVeterinaireCanPostOwnEvent(ApiTester $I){
        TypeEventFactory::createOne();
        $client = ClientFactory::createOne();
        $espece=EspeceFactory::createOne();
        AnimalFactory::createOne(
            [   'espece' => $espece,
                'client' => $client
            ]
        );
        $I->amLoggedInAs(VeterinaireFactory::createOne()->object());
        $dataInitPost=[
            "date"=> "2023-03-11T09:30:00+00:00",
            "description"=> "test1",
            "animal"=> "/api/animals/1",
            "typeEvent"=> "/api/type_events/1",
            "veterinaire"=> "/api/veterinaires/2"
        ];
        $I->sendPOST('/api/events',$dataInitPost);
        $I->seeResponseCodeIs(HttpCode::CREATED);

    }
    public function authenticatedClientCanPostOwnEvent(APITester $I){
        TypeEventFactory::createOne();
        VeterinaireFactory::createOne();
        $client = ClientFactory::createOne();
        $espece=EspeceFactory::createOne();
        AnimalFactory::createOne(
            [   'espece' => $espece,
                'client' => $client
            ]
        );
        $I->amLoggedInAs($client->object());
        $dataInitPost=[
            "date"=> "2023-03-11T09:30:00+00:00",
            "description"=> "test1",
            "animal"=> "/api/animals/1",
            "typeEvent"=> "/api/type_events/1",
            "veterinaire"=> "/api/veterinaires/1"
        ];
        $I->sendPOST('/api/events',$dataInitPost);
        $I->seeResponseCodeIs(HttpCode::CREATED);
    }

    public function authenticatedVeterinaireCantPostForOther(ApiTester $I ,)
    {   VeterinaireFactory::createOne();
        TypeEventFactory::createOne();
        $client = ClientFactory::createOne();
        $espece = EspeceFactory::createOne();
        AnimalFactory::createOne(
            ['espece' => $espece,
                'client' => $client,
            ]
        );
        
        $veterinaire2 = VeterinaireFactory::createOne();
        $I->amOnPage('/login');
        $I->amLoggedInAs($veterinaire2->object());
        $dataInitPost = [
           'date' => '2023-03-11T09:30:00+00:00',
           'description' => 'test1',
           'animal' => '/api/animals/1',
           'typeEvent' => '/api/type_events/1',
           'veterinaire' => '/api/veterinaires/1',
        ];
        try {
        $I->sendPOST('/api/events', $dataInitPost);
    }
     catch (AccessDeniedException $th) {
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
    }
    public function authenticatedClientCantPostForOther(ApiTester $I)
    {

        VeterinaireFactory::createOne();
        TypeEventFactory::createOne();
        $client = ClientFactory::createOne();
        $espece = EspeceFactory::createOne();
        AnimalFactory::createOne(
            ['espece' => $espece,
                'client' => $client,
            ]
        );
        $client2 = ClientFactory::createOne();
        $I->amOnPage('/login');
        $I->amLoggedInAs($client2->object());
        $dataInitPost = [
           'date' => '2023-03-11T09:30:00+00:00',
           'description' => 'test1',
           'animal' => '/api/animals/1',
           'typeEvent' => '/api/type_events/1',
           'veterinaire' => '/api/veterinaires/1',
        ];

        try {
            $I->sendPOST('/api/events', $dataInitPost);
        }
         catch (AccessDeniedException $th) {
            $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        }
    }
    public function adminCanPostOther(ApiTester $I)
    {
        VeterinaireFactory::createOne();
        TypeEventFactory::createOne();
        $client = ClientFactory::createOne();
        $espece = EspeceFactory::createOne();
        AnimalFactory::createOne(
            ['espece' => $espece,
                'client' => $client,
            ]
        );
        $admin= AdminFactory::createOne();
        $I->amOnPage('/login');
        $I->amLoggedInAs($admin->object());
        $dataInitPost = [
           'date' => '2023-03-11T09:30:00+00:00',
           'description' => 'test1',
           'animal' => '/api/animals/1',
           'typeEvent' => '/api/type_events/1',
           'veterinaire' => '/api/veterinaires/1',
        ];
        $I->sendPOST('/api/events', $dataInitPost);
        $I->seeResponseCodeIs(HttpCode::CREATED);
    }
}