<?php

namespace App\Jobs;

use App\Models\FleetCard;
use App\Models\Fuel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessFuelTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fleetCard;
    protected $fuel;

    /**
     * Create a new job instance.
     */
    public function __construct(FleetCard $fleetCard, Fuel $fuel)
    {
        $this->fleetCard = $fleetCard;
        $this->fuel = $fuel;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $userId = $this->fleetCard->user_id;
        $filename = $this->fuel->fuelNumber . '.pdf';
        $folderPath = "fuel_transactions/{$userId}/";
        $fullPath = "public/{$folderPath}{$filename}";

        // Ensure directory exists
        Storage::disk('public')->makeDirectory($folderPath);

        // Delete existing PDF if any
        if (Storage::disk('public')->exists($folderPath . $filename)) {
            Storage::disk('public')->delete($folderPath . $filename);
        }

        // Generate and store the PDF
        $pdf = Pdf::loadView('pdf.fuelTransaction', [
            'fleetCard' => $this->fleetCard,
            'fuel' => $this->fuel
        ]);

        Storage::disk('public')->put($folderPath . $filename, $pdf->output());

        // Log success
        Log::info("PDF saved at: " . storage_path("app/public/{$folderPath}{$filename}"));
    }
}
