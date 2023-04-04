<?php

declare(strict_types=1);


namespace App\Tests\Contact;

use App\Tests\Support\ApiTester;

class ContactCest
{
    public function contactWithAllParametersIsCreated(ApiTester $I): void
    {
        $I->sendPOST('/contact', [
            'email' => 'test@test.fr',
            'lastname' => 'test',
            'firstname' => 'test',
            'message' => 'test',
            'title' => 'test',
        ]);
        $I->seeResponseCodeIs(201);
    }
    public function contactWithNotAllParametersIsBadRequest(ApiTester $I): void
    {
    $I->sendPOST('/contact', [
        'email' => 'test',
    ]);
    $I->seeResponseCodeIs(400);
    }
}