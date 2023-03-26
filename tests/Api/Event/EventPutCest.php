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


class EventPut
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
    public function anonymousUserCannotPutEvent(ApiTester $I): void{
        $this->InitialiseData();
        $dataInitPost=[
            "id"=>2,
            "date"=> "2023-03-11T09:30:00+00:00",
            "description"=> "test1",
            "animal"=> "/api/animals/1",
            "typeEvent"=> "/api/typeEvents/1",
            "veterinaire"=> "/api/veterinaires/1"
        ];
        $I->sendPOST('/api/events',$dataInitPost);
        $dataInitPut=[
            "description"=> "test3",
        ];
        $I->sendPUT('/api/events/1',$dataInitPut);
    }
    public function authenticatedVeterinaireCanPutEvent(ApiTester $I){
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
            "typeEvent"=> "/api/typeEvents/1",
            "veterinaire"=> "/api/veterinaires/2"
        ];
        $I->sendPOST('/api/events',$dataInitPost);
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $dataInitPut=[
            "id"=>2,
            "description"=> "test2",
        ];
        $I->sendPut('/api/events/1',$dataInitPut);
        $I->seeResponseCodeIs(HttpCode::OK);
        $dataInitPut=[
            "description"=> "test3",
        ];
        $I->sendPUT('/api/events/1',$dataInitPut);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->sendDELETE('/api/events/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);

    }
    public function authenticatedClientCanPutOwnEvent(APITester $I){
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
            "typeEvent"=> "/api/typeEvents/1",
            "veterinaire"=> "/api/veterinaires/1"
        ];
        $I->sendPOST('/api/events',$dataInitPost);
        $dataInitPut=[
            "description"=> "test3",
        ];
        $I->sendPUT('/api/events/1',$dataInitPut);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function authenticatedVeterinaireCantPutForOther(ApiTester $I ,)
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
           'typeEvent' => '/api/typeEvents/1',
           'veterinaire' => '/api/veterinaires/1',
        ];
        
        $I->sendPOST('/api/events', $dataInitPost);
        $dataInitPut = [
            'description' => 'test2',
        ];
        $I->amLoggedInAs(VeterinaireFactory::createOne()->object());
    
        $I->sendPUT('/api/events/2', $dataInitPut);
    
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    
    }
    public function authenticatedClientCantPutForOther(ApiTester $I)
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
           'typeEvent' => '/api/typeEvents/1',
           'veterinaire' => '/api/veterinaires/1',
        ];

        $I->sendPOST('/api/events', $dataInitPost);
        $I->amLoggedInAs(ClientFactory::createOne()->object());
        $dataInitPut = [
            'description' => 'test2',
        ];

        try {
            $I->sendPUT('/api/events/2', $dataInitPut);
        } catch (AccessDeniedException $th) {
            $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        }
    }
    public function adminCanPutEventForOther(ApiTester $I)
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
           'typeEvent' => '/api/typeEvents/1',
           'veterinaire' => '/api/veterinaires/1',
        ];

        $I->sendPOST('/api/events', $dataInitPost);
        $I->amLoggedInAs(AdminFactory::createOne()->object());
        $dataInitPut = [
            'description' => 'test2',
        ];
        $I->sendPUT('/api/events/2', $dataInitPut);
        $I->seeResponseCodeIs(HttpCode::OK);
    }
    public function userCantPutEventBefore8OrAfter18(ApiTester $I)
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
        $I->sendPost('/api/events',
            ['date' => '2023-03-11T08:30:00+00:00',
                'description' => 'test1',
                'animal' => '/api/animals/1',
                'typeEvent' => '/api/typeEvents/1',
                'veterinaire' => '/api/veterinaires/1',
            ]
        );
        $dataInitPut = [
            'date' => '2023-03-11T07:30:00+00:00',
                'description' => 'test1',
                'animal' => '/api/animals/1',
                'typeEvent' => '/api/typeEvents/1',
                'veterinaire' => '/api/veterinaires/1',
            
        ];
        $I->sendPut('/api/events/1', $dataInitPut);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }
}
