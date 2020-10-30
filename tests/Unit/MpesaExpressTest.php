<?php

namespace Gathuku\Mpesa\Tests\Unit;

use Gathuku\Mpesa\Tests\BaseTest;
use Gathuku\Mpesa\Facades\Mpesa;

class MpesaExpressTest extends BaseTest
{
    public function test_mpesa_express()
    {
        $response = Mpesa::express(100, '254705112855', '24242524', 'Testing Payment');
        $data = json_decode($response, true);

        if (isset($data['CheckoutRequestID'])) {
            $this->assertArrayHasKey('MerchantRequestID', $data, "response don't have MerchantRequestID");
            $this->assertArrayHasKey('CheckoutRequestID', $data, "response don't have CheckoutRequestID");
            $this->assertArrayHasKey('ResponseDescription', $data, "response don't have ResponseDescription");
        }
    }
}
