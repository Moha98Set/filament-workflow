<?php

namespace App\Providers;

use App\Models\Registration;
use App\Observers\RegistrationObserver;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\HtmlString;

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

        \Filament\Support\Facades\FilamentView::registerRenderHook(
            'panels::head.end',
            fn () => new \Illuminate\Support\HtmlString('<style>
                html, body, .fi-layout, .fi-main, .fi-sidebar-nav, .fi-page, .fi-topbar {
                    direction: rtl !important;
                }
                .fi-sidebar {
                    border-left: 1px solid rgb(229 231 235);
                    border-right: none !important;
                }
                .fi-header-heading {
                    text-align: right !important;
                }
                table { direction: rtl !important; }
                th, td { text-align: right !important; }
                input, select, textarea {
                    direction: rtl !important;
                    text-align: right !important;
                }
            </style>')
        );
    }
}
