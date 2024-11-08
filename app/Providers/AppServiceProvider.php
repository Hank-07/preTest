<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Strategies\CurrencyConverterStrategy;
use App\Strategies\DefaultCurrencyConverter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CurrencyConverterStrategy::class, DefaultCurrencyConverter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
