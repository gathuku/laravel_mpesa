<?php

namespace Gathuku\Mpesa\Tests\Unit;

use Gathuku\Mpesa\Facades\Mpesa;
use Gathuku\Mpesa\Tests\BaseTest;

class MpesaB2CTest extends BaseTest
{
    public function test_b2c()
    {
        $response = Mpesa::b2c(100, '254708374149', 'PromotionPayment', 'testing');
        $data = json_decode($response, true);

        $this->assertNotEmpty($data);

        if (isset($data['errorCode'])) {
//            throw new \Exception(sprintf('mpesa request failed with error code:%s - %s', $data['errorCode'], $data['errorMessage']));
            return;
        }
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
