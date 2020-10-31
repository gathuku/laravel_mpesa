<?php

namespace Gathuku\Mpesa\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Gathuku\Mpesa\Tests\BaseTest;

class InstallMpesaTest extends BaseTest
{
    public function the_install_command_copies_a_the_configuration()
    {
        // remove if exists
        if (File::exists(config_path('mpesa.php'))) {
            unlink(config_path('mpesa.php'));
        }

        $this->assertFalse(File::exists(config_path('mpesa.php')));

        Artisan::call('mpesa:install');

        $this->assertTrue(File::exists(config_path('mpesa.php')));
    }
}
