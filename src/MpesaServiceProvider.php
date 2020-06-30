<?php

namespace Gathuku\Mpesa;

use Illuminate\Support\ServiceProvider;
use Gathuku\Mpesa\Console\InstallMpesa;

class MpesaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

       // require __DIR__.'/routes/web.php';
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        if($this->app->runningInConsole()){
          //publish the config files
          $this->publishes([
              __DIR__.'/../config/mpesa.php' => config_path('mpesa.php'),
          ],'mpesa-config');

          // Register commands
          $this->commands([
            InstallMpesa::class,
          ]);
        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mpesa.php', 'mpesa'); 

        $this->app->bind('gathuku-mpesa',function(){
             return new Mpesa();
        });
    }
}
