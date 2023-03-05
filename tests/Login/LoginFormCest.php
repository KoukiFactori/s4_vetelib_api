<?php

namespace App\Tests\Login;

use App\Factory\ClientFactory;
use App\Tests\Support\LoginTester;
use Codeception\Util\HttpCode;

class LoginFormCest
{
  public function submitValidFormShouldLogonUser(LoginTester $I)
  {
    ClientFactory::createOne([
      'email' => 'user1@dev',
      'password' => 'test'
    ]);

    $I->amOnPage('/login');
    $I->seeResponseCodeIsSuccessful();
    $I->submitForm(
        'form',
        ['login' => 'user1@dev', 'password' => 'test'],
        'Authentification'
    );
    $I->seeResponseCodeIsSuccessful();
    $I->seeCurrentRouteIs('api_doc');
  }

  public function logoutRouteShouldLogoutUser(LoginTester $I)
  {
    $user = ClientFactory::createOne();
    $I->amLoggedInAs($user->object());

    $I->amOnRoute('app_logout');
    $I->seeResponseCodeIs(HttpCode::OK);
    $I->seeCurrentRouteIs('api_doc');
  }
  
  public function loginRouteShouldDisplayUserIdentifierIfAlreadyConnected(LoginTester $I)
  {
    $user = ClientFactory::createOne();
    $I->amLoggedInAs($user->object());

    $I->amOnRoute('app_login');
    $I->see($user->getUserIdentifier());
  }
}