<?php

namespace App\Tests\Api\Event;

use App\Exception\PostEventAccessDeniedException;
use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Factory\EspeceFactory;
use App\Factory\EventFactory;
use App\Factory\TypeEventFactory;
use App\Factory\UserFactory;
use App\Factory\VeterinaireFactory;
use App\Repository\VeterinaireRepository;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;
use Symfony\Component\Security\Core\Security;

class EventPutPatchPostDeleteCest
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
    public function anonymousUserCannotPostPatchPutDeleteEvent(ApiTester $I): void{
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
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $dataInitPatch=[
            "id"=>2,
            "description"=> "test2",
        ];
        $I->sendPATCH('/api/events/1',$dataInitPatch);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $dataInitPut=[
            "description"=> "test3",
        ];
        $I->sendPUT('/api/events/1',$dataInitPut);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->sendDELETE('/api/events/1');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
    public function authenticatedVeterinaireCanPostPatchPutDeleteOwnEvent(ApiTester $I){
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
        $dataInitPut=[
            "description"=> "test3",
        ];
        $I->sendPUT('/api/events/1',$dataInitPut);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->sendDELETE('/api/events/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);

    }
    public function authenticatedClientCanPostPatchPutDeleteOwnEvent(APITester $I){
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
        $dataInitPatch=[
            "id"=>2,
            "description"=> "test2",
        ];
        $I->sendPATCH('/api/events/1',$dataInitPatch);
        $I->seeResponseCodeIs(HttpCode::OK);
        $dataInitPut=[
            "description"=> "test3",
        ];
        $I->sendPUT('/api/events/1',$dataInitPut);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->sendDELETE('/api/events/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    public function authenticatedVeterinaireCantPutPatchDeleteForOther(ApiTester $I ,)
    {   $veterinaire = VeterinaireFactory::createOne();
        $type = TypeEventFactory::createOne();
        $client = ClientFactory::createOne();
        $espece = EspeceFactory::createOne();
        $animal = AnimalFactory::createOne(
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
     catch (PostEventAccessDeniedException $th) {
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
    $dataInitPatch = [
        'description' => 'test2',
    ];

    try {
        $I->sendPATCH('/api/events/2', $dataInitPatch);
    } catch (PostEventAccessDeniedException $th) {
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
    try {
        $I->sendPUT('/api/events/2', $dataInitPatch);
    } catch (PostEventAccessDeniedException $th) {
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
    try {
        $I->sendDELETE('/api/events/2');
    } catch (PostEventAccessDeniedException $th) {
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
    }
    public function authenticatedClientCantPutPatchDeleteForOther(ApiTester $I)
    {

        $veterinaire = VeterinaireFactory::createOne();
        $type = TypeEventFactory::createOne();
        $client = ClientFactory::createOne();
        $espece = EspeceFactory::createOne();
        $client2 = ClientFactory::createOne();
        $animal = AnimalFactory::createOne(
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
         catch (PostEventAccessDeniedException $th) {
            $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        }
        $dataInitPatch = [
            'description' => 'test2',
        ];
    
        try {
            $I->sendPATCH('/api/events/2', $dataInitPatch);
        } catch (PostEventAccessDeniedException $th) {
            $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        }
        try {
            $I->sendPUT('/api/events/2', $dataInitPatch);
        } catch (PostEventAccessDeniedException $th) {
            $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        }
        try {
            $I->sendDELETE('/api/events/2');
        } catch (PostEventAccessDeniedException $th) {
            $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        }

    }
}


