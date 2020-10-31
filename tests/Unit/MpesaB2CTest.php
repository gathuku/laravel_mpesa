<?php

namespace Gathuku\Mpesa\Tests\Unit;

use Gathuku\Mpesa\Tests\BaseTest;
use Gathuku\Mpesa\Facades\Mpesa;

class MpesaB2CTest extends BaseTest
{
    public function test_b2c()
    {
        $response = Mpesa::b2c(100, '254708374149', 'PromotionPayment', 'testing');
        $data = json_decode($response, true);
        //$this->assertTrue($response);
        $this->assertArrayHasKey(
            'ConversationID',
            $data,
            "response don't have ConversationID"
        );
        $this->assertArrayHasKey(
            'OriginatorConversationID',
            $data,
            "response dont have OriginatorConversationID"
        );
        $this->assertArrayHasKey(
            'ResponseDescription',
            $data,
            "response dont have ResponseDescription"
        );
    }
}
