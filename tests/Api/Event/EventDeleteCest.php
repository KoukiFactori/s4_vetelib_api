<?php

namespace App\Tests\Api\Event;

use App\Factory\AdminFactory;
use Codeception\Util\HttpCode;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Factory\EventFactory;
use App\Factory\TypeEventFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;


class EventDelete
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
    public function anonymousUserCannotDeleteEvent(ApiTester $I): void{
        $this->InitialiseData();
        $dataInitPost=[
            "id"=>2,
            "date"=> "2023-03-11T09:30:00+00:00",
            "description"=> "test1",
            "animal"=> "/api/animals/1",
            "typeEvent"=> "/api/type_events/1",
            "veterinaire"=> "/api/veterinaires/1"
        ];
        $I->sendPOST('/api/events',$dataInitPost);
        $dataInitDelete=[
            "description"=> "test3",
        ];
        $I->sendDelete('/api/events/1',$dataInitDelete);
    }
    public function authenticatedVeterinaireCanDeleteEvent(ApiTester $I){
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

        $I->sendDELETE('/api/events/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);

    }
    public function authenticatedClientCanDeleteOwnEvent(APITester $I){
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
        $I->sendDelete('/api/events/1');
        $I->seeResponseCodeIs(HttpCode::No_CONTENT);
    }

    public function authenticatedVeterinaireCantDeleteForOther(ApiTester $I ,)
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
        
        $I->sendPOST('/api/events', $dataInitPost);

        $I->amLoggedInAs(VeterinaireFactory::createOne()->object());
    try {
        $I->sendDelete('/api/events/2');
    } catch (AccessDeniedException $th) {
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
    }
    public function authenticatedClientCantDeleteForOther(ApiTester $I)
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
       
        $I->amOnPage('/login');
        $I->amLoggedInAs($client->object());
        $dataInitPost = [
           'date' => '2023-03-11T09:30:00+00:00',
           'description' => 'test1',
           'animal' => '/api/animals/1',
           'typeEvent' => '/api/type_events/1',
           'veterinaire' => '/api/veterinaires/1',
        ];

        $I->sendPOST('/api/events', $dataInitPost);
        $I->amLoggedInAs(ClientFactory::createOne()->object());
        try {
            $I->sendDelete('/api/events/2');
        } catch (AccessDeniedException $th) {
            $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        }
    }
    public function adminCanDeleteEventForOther(ApiTester $I)
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
        $I->amOnPage('/login');
        $I->amLoggedInAs($client->object());
        $dataInitPost = [
           'date' => '2023-03-11T09:30:00+00:00',
           'description' => 'test1',
           'animal' => '/api/animals/1',
           'typeEvent' => '/api/type_events/1',
           'veterinaire' => '/api/veterinaires/1',
        ];

        $I->sendPOST('/api/events', $dataInitPost);
        $I->amLoggedInAs(AdminFactory::createOne()->object());

        $I->sendDelete('/api/events/2');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }
}
