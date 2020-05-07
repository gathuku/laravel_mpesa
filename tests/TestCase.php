<?php

namespace Gathuku\Mpesa\Tests;

use Orchestra\Testbench\TestCase;
use Gathuku\Mpesa\MpesaServiceProvider;

class ExampleTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
      // environment setup
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
