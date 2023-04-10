<?php
declare(strict_types=1);

namespace App\Tests\Api\Veterinaire;

use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class FetchAllVeterinarianCest
{
  public function routeReturnsValidHTTPResponseCode(ApiTester $tester)
  {
    $tester->sendGet('/api/veterinaires');
    $tester->seeResponseCodeIs(HttpCode::OK);
  }

  public function routeReturnsValidSchema(ApiTester $tester)
  {
    
  }
}