<?php

namespace App\Tests\Api\Event;

use App\Factory\AdminFactory;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Factory\EventFactory;
use App\Factory\TypeEventFactory;
use App\Factory\VeterinaireFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;


class EventPatch
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
    public function anonymousUserCannotPatchEvent(ApiTester $I): void{
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
        $dataInitPatch=[
            "description"=> "test3",
        ];
        $I->sendPatch('/api/events/1',$dataInitPatch);
    }
    public function authenticatedVeterinaireCanPatchEvent(ApiTester $I){
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
        $dataInitPatch=[
            "id"=>2,
            "description"=> "test2",
        ];
        $I->sendPATCH('/api/events/1',$dataInitPatch);
        $I->seeResponseCodeIs(HttpCode::OK);
        $dataInitPatch=[
            "description"=> "test3",
        ];
        $I->sendPatch('/api/events/1',$dataInitPatch);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->sendDELETE('/api/events/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);

    }
    public function authenticatedClientCanPatchOwnEvent(APITester $I){
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
        $dataInitPatch=[
            "description"=> "test3",
        ];
        $I->sendPatch('/api/events/1',$dataInitPatch);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function authenticatedVeterinaireCantPatchForOther(ApiTester $I ,)
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
        $dataInitPatch = [
            'description' => 'test2',
        ];
        $I->amLoggedInAs(VeterinaireFactory::createOne()->object());
   
        $I->sendPatch('/api/events/2', $dataInitPatch);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
    public function authenticatedClientCantPatchForOther(ApiTester $I)
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
        $dataInitPatch = [
            'description' => 'test2',
        ];

    
            $I->sendPatch('/api/events/2', $dataInitPatch);
            $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        
    }
    public function adminCanPatchEventForOther(ApiTester $I)
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
        $dataInitPatch = [
            'description' => 'test2',
        ];
        $I->sendPatch('/api/events/2', $dataInitPatch);
        $I->seeResponseCodeIs(HttpCode::OK);
    }
    public function userCantPatchEventBefore8(ApiTester $I)
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
        EventFactory::createOne(
            ['date' => '2023-03-11T08:30:00+00:00',
                'description' => 'test1',
                'animal' => '/api/animals/1',
                'typeEvent' => '/api/type_events/1',
                'veterinaire' => '/api/veterinaires/1',
            ]
        );
        $I->amOnPage('/login');
        $I->amLoggedInAs($client->object());

        $dataInitPatch = [
            'date' => '2023-03-11T07:30:00+00:00',
        ];
        $I->sendPatch('/api/events/1', $dataInitPatch);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }
    public function userCantPatchEventAfter18(ApiTester $I){
        VeterinaireFactory::createOne();
        TypeEventFactory::createOne();
        $client = ClientFactory::createOne();
        $espece = EspeceFactory::createOne();
        AnimalFactory::createOne(
            ['espece' => $espece,
                'client' => $client,
            ]
        );
        EventFactory::createOne(
            ['date' => '2023-03-11T08:30:00+00:00',
                'description' => 'test1',
                'animal' => '/api/animals/1',
                'typeEvent' => '/api/type_events/1',
                'veterinaire' => '/api/veterinaires/1',
            ]
        );
        $I->amOnPage('/login');
        $I->amLoggedInAs($client->object());

        $dataInitPatch = [
            'date' => '2023-03-11T19:30:00+00:00',
        ];
        $I->sendPatch('/api/events/1', $dataInitPatch);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }
    public function eventStartCanOnlyStartAt30Or00(ApiTester $I){
        VeterinaireFactory::createOne();
        TypeEventFactory::createOne();
        $client = ClientFactory::createOne();
        $espece = EspeceFactory::createOne();
        AnimalFactory::createOne(
            ['espece' => $espece,
                'client' => $client,
            ]
        );
        EventFactory::createOne(
            ['date' => '2023-03-11T08:30:00+00:00',
                'description' => 'test1',
                'animal' => '/api/animals/1',
                'typeEvent' => '/api/type_events/1',
                'veterinaire' => '/api/veterinaires/1',
            ]
        );
        $I->amOnPage('/login');
        $I->amLoggedInAs($client->object());

        $dataInitPatch = [
            'date' => '2023-03-11T08:15:00+00:00',
        ];
        $I->sendPatch('/api/events/1', $dataInitPatch);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }
}
