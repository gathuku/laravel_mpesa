<?php

namespace Gathuku\Mpesa\Tests;

use Orchestra\Testbench\TestCase;
use Gathuku\Mpesa\MpesaServiceProvider;

class BaseTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        // environment setup
      // config()->set('mpesa_env','sandbox');
      // config()->set('consumer_key','ZtkRW6ATbVtFpNml5w5SfG26Adfyagn9');
      // config()->set('consumer_secret','dosFI1yQ8bvHEVFw');
      //
    }

    public function setUp():void
    {
        parent::setup();
        //additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
          MpesaServiceProvider::class
        ];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
