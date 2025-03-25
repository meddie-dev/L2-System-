<?php

namespace App\Jobs;

use App\Models\TripTicket;
use App\Models\Maintenance;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CheckMaintenance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $vehicle_id;

    /**
     * Create a new job instance.
     */
    public function __construct($vehicle_id)
    {
        $this->vehicle_id = $vehicle_id;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info("🔧 CheckMaintenance Job Started for Vehicle ID: {$this->vehicle_id}");

        $vehicle = Vehicle::find($this->vehicle_id);

        if (!$vehicle) {
            Log::warning("🚨 Vehicle ID {$this->vehicle_id} not found. Exiting job.");
            return;
        }

        // Get total distance traveled by the vehicle
        $totalDistance = TripTicket::where('vehicle_id', $this->vehicle_id)->sum('distance');
        Log::info("📊 Total Distance for Vehicle ID {$this->vehicle_id}: {$totalDistance} km");

        // ✅ Define maintenance schedules (KM thresholds)
        $maintenanceTasks = [
            'Oil Change & Filter Replacement' => 5000,
            'Brake System Check (Pads, Fluid, etc.)' => 10000,
            'Tire Rotation & Alignment' => 10000,
            'Transmission Fluid Check & Change' => 30000,
            'Battery Check & Cleaning' => 6000,
            'Coolant System (Radiator Flush & Refill)' => 40000,
            'Air & Fuel Filter Replacement' => 20000,
            'Suspension & Steering System Check' => 20000,
        ];

        // ✅ Check for required maintenance based on distance
        foreach ($maintenanceTasks as $task => $threshold) {
            if ($totalDistance >= $threshold) {
                Log::info("🔍 Task '{$task}' meets threshold ({$threshold} km). Checking if it exists...");

                $cost = $this->getMaintenanceCost($task, $vehicle->vehicleType);
                $this->createMaintenanceIfNotExists($task, $cost, $vehicle);
            } else {
                Log::info("⏳ Task '{$task}' not yet due (Current: {$totalDistance} km, Required: {$threshold} km)");
            }
        }

        // ✅ Check for yearly maintenance
        $this->checkAnnualMaintenance($vehicle);
    }

    /**
     * Get maintenance cost based on vehicle type.
     */
    private function getMaintenanceCost($task, $vehicleType)
    {
        $costs = [
            'light' => [
                'Oil Change & Filter Replacement' => 1500,
                'Brake System Check (Pads, Fluid, etc.)' => 3000,
                'Tire Rotation & Alignment' => 2500,
                'Transmission Fluid Check & Change' => 6000,
                'Battery Check & Cleaning' => 1200,
                'Coolant System (Radiator Flush & Refill)' => 3500,
                'Air & Fuel Filter Replacement' => 2500,
                'Suspension & Steering System Check' => 5000,
                'Full Engine Diagnostics' => 4000,
                'Annual General Inspection (LTO Required)' => 2500,
            ],
            'medium' => [
                'Oil Change & Filter Replacement' => 2500,
                'Brake System Check (Pads, Fluid, etc.)' => 4500,
                'Tire Rotation & Alignment' => 4000,
                'Transmission Fluid Check & Change' => 9000,
                'Battery Check & Cleaning' => 2000,
                'Coolant System (Radiator Flush & Refill)' => 5000,
                'Air & Fuel Filter Replacement' => 4000,
                'Suspension & Steering System Check' => 7000,
                'Full Engine Diagnostics' => 6000,
                'Annual General Inspection (LTO Required)' => 4000,
            ],
            'heavy' => [
                'Oil Change & Filter Replacement' => 5000,
                'Brake System Check (Pads, Fluid, etc.)' => 7500,
                'Tire Rotation & Alignment' => 6000,
                'Transmission Fluid Check & Change' => 15000,
                'Battery Check & Cleaning' => 3500,
                'Coolant System (Radiator Flush & Refill)' => 8000,
                'Air & Fuel Filter Replacement' => 7000,
                'Suspension & Steering System Check' => 12000,
                'Full Engine Diagnostics' => 10000,
                'Annual General Inspection (LTO Required)' => 7000,
            ],
        ];

        return $costs[$vehicleType][$task] ?? 0;
    }

    private function generateMaintenancePDF($maintenance, $vehicle)
    {
        try {
            $filename = "{$maintenance->maintenanceNumber}.pdf";
            $folderPath = "maintenance_reports/{$vehicle->id}/";
            $fullPath = "{$folderPath}{$filename}";
    
            // ✅ Ensure directory exists
            Storage::disk('public')->makeDirectory($folderPath);
    
            // ✅ Delete old PDF if exists
            if (Storage::disk('public')->exists($fullPath)) {
                Storage::disk('public')->delete($fullPath);
            }
    
            // ✅ Generate PDF
            $pdf = Pdf::loadView('pdf.maintenanceReport', [
                'maintenance' => $maintenance,
                'vehicle' => $vehicle,
            ]);
    
            // ✅ Save PDF
            Storage::disk('public')->put($fullPath, $pdf->output());
    
            Log::info("📄 Maintenance PDF saved at: storage/app/public/{$fullPath}");
        } catch (\Exception $e) {
            Log::error("🚨 Failed to generate PDF: " . $e->getMessage());
        }
    }
    
    private function createMaintenanceIfNotExists($task, $cost, $vehicle)
    {
        $exists = Maintenance::where('vehicle_id', $this->vehicle_id)
            ->where('task', $task)
            ->whereNull('completed_date')
            ->exists();

        if (!$exists) {
            Log::info("✅ Creating maintenance record for '{$task}' (Cost: PHP {$cost})");

            $maintenance = Maintenance::create([
                'vehicle_id' => $this->vehicle_id,
                'maintenanceNumber' => strtoupper(uniqid('MTN-')),
                'task' => $task,
                'amount' => $cost,
                'scheduled_date' => now(),
                'completed_date' => now()->addWeek(),
                'conditionStatus' => 'poor',
            ]);

            Vehicle::where('id', $this->vehicle_id)->update(['vehicleStatus' => 'maintenance']);

            $this->generateMaintenancePDF($maintenance, $vehicle);

        } else {
            Log::info("🚫 Maintenance '{$task}' already exists. Skipping.");
        }
    }

    private function checkAnnualMaintenance($vehicle)
    {
        Log::info("🔄 Checking annual maintenance for Vehicle ID: {$vehicle->id}");

        if (Carbon::parse($vehicle->created_at)->lessThanOrEqualTo(Carbon::now()->subYear())) {
            Log::info("📅 Annual maintenance is due.");
            $tasks = ['Full Engine Diagnostics', 'Annual General Inspection (LTO Required)'];

            foreach ($tasks as $task) {
                $cost = $this->getMaintenanceCost($task, $vehicle->vehicleType);
                $this->createMaintenanceIfNotExists($task, $cost, $vehicle);
            }
        } else {
            Log::info("🕒 Annual maintenance not due yet.");
        }
    }

    private function createAnnualMaintenanceIfNotExists($task, $cost, $vehicle)
{
    $exists = Maintenance::where('vehicle_id', $this->vehicle_id)
        ->where('task', $task)
        ->whereYear('scheduled_date', now()->year)
        ->exists();

    if (!$exists) {
        Log::info("✅ Creating annual maintenance record for '{$task}' (Cost: PHP {$cost})");

        // ✅ Store the new maintenance record
        $maintenance = Maintenance::create([
            'vehicle_id' => $this->vehicle_id,
            'maintenanceNumber' => Str::upper(Str::random(20)),
            'task' => $task,
            'amount' => $cost,
            'scheduled_date' => now(),
            'completed_date' => now()->addWeek(),
            'conditionStatus' => 'poor',
        ]);

        // ✅ Generate and store PDF
        $this->generateMaintenancePDF($maintenance, $vehicle);
    } else {
        Log::info("🚫 Annual maintenance '{$task}' already exists for this year. Skipping.");
    }
}

}
