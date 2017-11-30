<?php

namespace Smsa;

use Illuminate\Support\ServiceProvider;

class SmsaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
      if(!file_exists(config_path('smsa.php'))){
        $this->publishes([
          __DIR__.'/../config/smsa.php' => config_path('smsa.php')
        ]);

        $this->publishes([
          __DIR__.'/../public' => public_path(''),
        ]);
      }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->singleton('Smsa', function ($app) {
        $smsa = new SmsaClient();
        return $smsa;
      });
    }

}
