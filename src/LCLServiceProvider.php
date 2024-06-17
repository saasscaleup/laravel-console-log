<?php

namespace Saasscaleup\LCL;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Log\Events\MessageLogged;

class LCLServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
     
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/Http/routes.php';
        }

        $this->loadViewsFrom(__DIR__ . '/Views', 'lcl');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            // Publish the configuration file.
            $this->publishes([
                __DIR__ . '/Config/lcl.php' => config_path('lcl.php'),
            ], 'lcl.config');

            // Publish the views.
            $this->publishes([
                __DIR__ . '/Views' => base_path('resources/views/vendor/lcl'),
            ], 'lcl.views');

            // Publish the migrations.
            $this->publishes([
                __DIR__ . '/Migrations' => database_path('migrations')
            ]);
        }

        // If Log listener enabled
        if (config('lcl.log_enabled')){

            // register event handler
            Event::listen(MessageLogged::class, function (MessageLogged $e) {

                // If log type in array
                if (in_array($e->level,explode(',',config('lcl.log_type')))){

                    
                    $message = empty($e->context) ? $e->message : $e->message.' : '.json_encode($e->context);

                    if (config('lcl.log_specific')!==''){
                        if (str_contains($message,config('lcl.log_specific')) ){
                            stream_console_log($message,$e->level,'stream-console-log');
                        }
                    }else{
                        stream_console_log($message,$e->level,'stream-console-log');
                    }
                }
            }); 

        }
    }

    /**
     * Register package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/lcl.php', 'lcl');

        // Register the service package provides.
        $this->app->singleton('LCL', function () {
            return $this->app->make(LCL::class);
        });
    }
}
