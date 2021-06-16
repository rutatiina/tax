<?php

namespace Rutatiina\Tax;

use Illuminate\Support\ServiceProvider;

class TaxServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes/routes.php';
        //include __DIR__.'/routes/api.php';

        $this->loadViewsFrom(__DIR__.'/resources/views', 'tax');
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Rutatiina\Tax\Http\Controllers\TaxController');
    }
}
