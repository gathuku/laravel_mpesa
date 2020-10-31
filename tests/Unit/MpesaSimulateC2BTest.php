<?php

namespace Gathuku\Mpesa\Tests\Unit;

use Gathuku\Mpesa\Tests\BaseTest;
use Gathuku\Mpesa\Facades\Mpesa;

class MpesaSimeulateC2BTest extends BaseTest
{
    public function it_can_simulate()
    {
        $response = Mpesa::simulateC2B(100, "254708374149", "Testing");
        $data = json_decode($response, true);
        //$this->assertTrue($response);
        $this->assertArrayHasKey('ConversationID', $data);
        $this->assertArrayHasKey('OriginatorConversationID', $data);
        $this->assertArrayHasKey('ResponseDescription', $data);
    }
}
