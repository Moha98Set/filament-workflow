<?php

namespace App\Providers;

use App\Models\Registration;
use App\Observers\RegistrationObserver;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Registration::observe(RegistrationObserver::class);
        Vite::prefetch(concurrency: 3);
    }
}
