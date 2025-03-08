<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    protected $listen = [
        \App\Events\VehicleReservationApproved::class => [
        ],
    ];
    
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
        date_default_timezone_set('Asia/Manila'); // Ensure PHP uses the correct timezone globally

        Carbon::macro('formatDateTime', function () {
            return $this->format('F j, Y, g:i a');
        });
    }
}
