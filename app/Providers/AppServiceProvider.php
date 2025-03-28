<?php

namespace App\Providers;

use App\Models\FleetCard;
use App\Models\IncidentReport;
use App\Models\Modules\Document;
use App\Models\Modules\Order;
use App\Models\Modules\VehicleReservation;
use App\Models\Payment;
use App\Models\TripTicket;
use App\Policies\DriverPolicy;
use App\Policies\StaffPolicy;
use App\Policies\VendorPolicy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Google\Client as Google_Client;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;
use Google\Service\Drive as Google_Service_Drive;
use Illuminate\Support\Facades\Gate;

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
            return $this->format('Y-m-d h:i A');
        });

        // Staff
        Gate::policy(Order::class, StaffPolicy::class);
        Gate::policy(IncidentReport::class, StaffPolicy::class);
        Gate::policy(Document::class, StaffPolicy::class);
        Gate::policy(Payment::class, StaffPolicy::class);
        Gate::policy(VehicleReservation::class, StaffPolicy::class);

        // Vendor
        Gate::policy(Order::class, VendorPolicy::class);
        Gate::policy(VehicleReservation::class, VendorPolicy::class);
        Gate::policy(Document::class, VendorPolicy::class);
        Gate::policy(Payment::class, VendorPolicy::class);

        // Driver
        Gate::policy(VehicleReservation::class, DriverPolicy::class);
        Gate::policy(IncidentReport::class, DriverPolicy::class);
        Gate::policy(TripTicket::class, DriverPolicy::class);
        Gate::policy(FleetCard::class, DriverPolicy::class);

    }
}
