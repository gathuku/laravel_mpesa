<?php

namespace Gathuku\Mpesa\Tests\Unit;

use Gathuku\Mpesa\Tests\BaseTest;
use Gathuku\Mpesa\Facades\Mpesa;

class MpesaRegisterUrlsTest extends BaseTest
{
  public function it_can_register_urls()
  {
    $response = Mpesa::registerUrls();
    $data = json_decode($response, true);
    $this->assertTrue($response);
    $this->assertEqual(200,$response->getStatusCode());
    $this->assertTrue($response->hasHeader('content-type'));
    $this->assertArrayHasKey('ConversationID',$data);
    $this->assertArrayHasKey('OriginatorCoversationID',$data);
    $this->assertArrayHasKey('ResponseDescription',$data);
  }
}
