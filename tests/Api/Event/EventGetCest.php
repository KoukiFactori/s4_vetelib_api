<?php

namespace App\Tests\Api\Event;

use App\Entity\Event;
use App\Factory\EventFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class UserGetMeCest
{
    public function anonymousUserCannotGetEvent(ApiTester $I): void
    {
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
                'veterinaire' =>'/api/veterinaires/'.$veterinaire2->getId(),
            ]
        );
        $I->sendGET('/api/events/1');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}
