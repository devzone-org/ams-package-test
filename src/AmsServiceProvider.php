<?php

namespace Devzone\Ams;

use Devzone\Ams\Console\DumpMasterData;
use Devzone\Ams\Http\Livewire\ChartOfAccount\Add;
use Devzone\Ams\Http\Livewire\ChartOfAccount\Listing;
use Devzone\Ams\Http\Livewire\Journal\Close;
use Devzone\Ams\Http\Livewire\Journal\Edit;
use Devzone\Ams\Http\Livewire\Journal\TempList;
use Devzone\Ams\Http\Livewire\Journal\TraceVoucher;
use Devzone\Ams\Http\Livewire\Post\Show;
use Devzone\Ams\Http\Livewire\Reports\BalanceSheet;
use Devzone\Ams\Http\Livewire\Reports\DayClosing;
use Devzone\Ams\Http\Livewire\Reports\Ledger;
use Devzone\Ams\Http\Livewire\Reports\ProfitLoss;
use Devzone\Ams\Http\Livewire\Reports\ProfitLossDateWise;
use Devzone\Ams\Http\Livewire\Reports\Trial;
use Devzone\Ams\Http\Livewire\Sidebar\SidebarLinks;
use Devzone\Ams\Http\Livewire\PettyExpenses;
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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'ams');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
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

            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    protected function routeConfiguration(): array
    {
        return [
            'prefix' => config('ams.prefix'),
            'middleware' => config('ams.middleware'),
        ];
    }

    private function registerLivewireComponent()
    {
        Livewire::component('chart-of-accounts.listing', Listing::class);
        Livewire::component('chart-of-accounts.add', Add::class);
        Livewire::component('journal.add', \Devzone\Ams\Http\Livewire\Journal\Add::class);
        Livewire::component('journal.edit', Edit::class);
        Livewire::component('journal.temp-list', TempList::class);
        Livewire::component('journal.trace-voucher', TraceVoucher::class);
        Livewire::component('reports.ledger', Ledger::class);
        Livewire::component('reports.trial', Trial::class);
        Livewire::component('reports.profit-loss', ProfitLoss::class);

        Livewire::component('reports.profit-loss-datewise', ProfitLossDateWise::class);
        Livewire::component('reports.day-closing', DayClosing::class);
        Livewire::component('reports.balance-sheet', BalanceSheet::class);
        Livewire::component('journal.close', Close::class);
        Livewire::component('journal.payments.listing', \Devzone\Ams\Http\Livewire\Journal\Payment\Listing::class);
        Livewire::component('journal.payments.add', \Devzone\Ams\Http\Livewire\Journal\Payment\Add::class);
        Livewire::component('sidebar.sidebar-links', SidebarLinks::class);
        Livewire::component('petty-expenses.add-petty-expenses', PettyExpenses\AddPettyExpenses::class);
        Livewire::component('petty-expenses.petty-expenses-list', PettyExpenses\PettyExpensesList::class);
        Livewire::component('petty-expenses.tab', PettyExpenses\Tab::class);
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
            __DIR__ . '/../config/ams.php' => config_path('ams.php'),
        ], 'ams.config');

        // Publishing the views.
        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/ams'),
        ], 'ams.views');

        // Publishing assets.
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('ams'),
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

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ams.php', 'ams');

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
}
