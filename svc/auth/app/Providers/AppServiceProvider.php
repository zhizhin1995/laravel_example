<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Services\Currency\CurrencyService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CurrencyService::class, function($app) {
            return new CurrencyService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
