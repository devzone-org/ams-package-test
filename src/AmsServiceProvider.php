<?php

namespace Devzone\Ams;

use Devzone\Ams\Console\DumpMasterData;
use Devzone\Ams\Http\Livewire\ChartOfAccount\Add;
use Devzone\Ams\Http\Livewire\ChartOfAccount\Listing;
use Devzone\Ams\Http\Livewire\Post\Show;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AmsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'ams');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ams');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->registerRoutes();


        $this->registerLivewireComponent();
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();

        }


    }


    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    protected function routeConfiguration(): array
    {
        return [
            'prefix' => config('ams.prefix'),
            'middleware' => config('ams.middleware'),
        ];
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ams.php', 'ams');

        // Register the service the package provides.
        $this->app->singleton('ams', function ($app) {
            return new Ams;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['ams'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/ams.php' => config_path('ams.php'),
        ], 'ams.config');

        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/ams'),
        ], 'ams.views');

        // Publishing assets.
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('ams'),
        ], 'ams.assets');

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/devzone'),
        ], 'ams.views');*/

        // Registering package commands.
         $this->commands([
             DumpMasterData::class,
         ]);
    }

    private function registerLivewireComponent(){
        Livewire::component('chart-of-accounts.listing',Listing::class);
        Livewire::component('chart-of-accounts.add',Add::class);
        Livewire::component('journal.add',\Devzone\Ams\Http\Livewire\Journal\Add::class);
    }
}
