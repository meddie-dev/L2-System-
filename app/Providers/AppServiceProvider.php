<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Google\Client as Google_Client;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;
use Google\Service\Drive as Google_Service_Drive;


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

        Storage::extend('google', function ($app, $config) {
            $client = new Google_Client();
            $client->setClientId(config('filesystems.disks.google.client_id'));
            $client->setClientSecret(config('filesystems.disks.google.client_secret'));
            $client->refreshToken(config('filesystems.disks.google.refresh_token'));
            
            $service = new Google_Service_Drive($client);
            
            // List all files
            $files = $service->files->listFiles();
            
            foreach ($files->getFiles() as $file) {
                echo "Name: " . $file->getName() . "\n";
                echo "ID: " . $file->getId() . "\n";
                echo "MIME Type: " . $file->getMimeType() . "\n";
                echo "-------------------------\n";
            }
            
            

            
    
            return new Filesystem($adapter);
        });
    }
}
