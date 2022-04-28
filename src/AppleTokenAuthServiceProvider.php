<?php

namespace AnimusCoop\AppleTokenAuth;

use AnimusCoop\AppleTokenAuth\Classes\AppleAuth;
use AnimusCoop\AppleTokenAuth\Console\Kernel;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use AnimusCoop\AppleTokenAuth\Console\Commands\AppleKeyGenerate;

class AppleTokenAuthServiceProvider extends ServiceProvider
{

    protected $commands = [
        AppleKeyGenerate::class,
    ];
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootAppleProvide();
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAppleScheduler();
        $this->app->singleton(AppleAuth::class, function () {
            return new AppleAuth($configData = []);
        });
    }

    private function bootAppleProvide()
    {
        $this->commands($this->commands);
    }

    private function registerAppleScheduler()
    {
        $this->app->singleton('animuscoop.appletokenauth.console.kernel', function($app) {
            $dispatcher = $app->make(Dispatcher::class);
            return new Kernel($app, $dispatcher);
        });

        $this->app->make('animuscoop.appletokenauth.console.kernel');
    }
}
