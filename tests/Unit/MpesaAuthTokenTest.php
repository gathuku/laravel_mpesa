<?php

namespace Gathuku\Mpesa\Tests\Unit;

use Gathuku\Mpesa\Tests\BaseTest;
use Gathuku\Mpesa\Facades\Mpesa;

class MpesaAuthTokenTest extends BaseTest
{
    public function it_can_get_token()
    {
        $response = Mpesa::getAccessToken();
        $this->assertTrue($response);
    }
}
