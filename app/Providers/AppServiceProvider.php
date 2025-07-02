<?php

namespace App\Providers;
use App\View\Composers\NotificationComposer; 
use Illuminate\Support\Facades\View; 

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
        View::composer(
            ['partials.navbar', 'adminlte::page'], // Target view, bisa satu atau banyak
            NotificationComposer::class
        );
    }
}
