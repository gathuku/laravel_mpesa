<?php

namespace Gathuku\Mpesa\Tests\Unit;

use Gathuku\Mpesa\Tests\BaseTest;
use Gathuku\Mpesa\Facades\Mpesa;

class MpesaExpressTest extends BaseTest
{
  public function test_mpesa_express()
  {
    $response = Mpesa::express(100,'2547112855','24242524','Testing Payment');
    $data = json_decode($response,true);
    $this->assertTrue($response);
    $this->assertArrayHasKey('MerchantRequestID',$data);
    $this->assertArrayHasKey('CheckoutRequestID',$data);
    $this->assertArrayHasKey('ResponseDescription',$data);
  }
}
