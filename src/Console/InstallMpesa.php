<?php

namespace Gathuku\Mpesa\Console;

use Illuminate\Console\Command;

class InstallMpesa extends Command
{
    protected $signature = "mpesa:install";

    protected $description = "Install Mpesa packege";

    public function handle()
    {
        $this->info('Installing Laravel Mpesa...');
        $this->info('Publishing Configuration...');

        $this->call('vendor:publish', [
      '--provider' => "Gathuku\Mpesa\MpesaServiceProvider",
      '--tag' => "mpesa-config"
    ]);

        $this->info('Installed Laravel Mpesa');
    }
}
