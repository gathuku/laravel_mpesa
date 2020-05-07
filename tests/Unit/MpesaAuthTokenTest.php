<?php

namespace Gathuku\Mpesa\Tests\Unit;

use Gathuku\Mpesa\Tests\TestCase;
use Gathuku\Mpesa\Facades\Mpesa;

class MpesaAuthTokenTest extends Testcase
{

  function it_can_get_token(){
    $response = Mpesa::getAccessToken();
    $this->assertTrue($response);
  }

}
