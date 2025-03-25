<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehicle;
use App\Models\Maintenance;
use App\Models\AssetReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ComputeAssetDepreciationCommand extends Command
{
    protected $signature = 'compute:depreciation';
    protected $description = 'Compute asset depreciation for all vehicles';

    public function handle()
    {
        Log::info("📉 Starting asset depreciation computation for all vehicles...");

        $vehicles = Vehicle::all();

        if ($vehicles->isEmpty()) {
            Log::warning("🚨 No vehicles found. Exiting command.");
            $this->error("No vehicles found!");
            return;
        }

        foreach ($vehicles as $vehicle) {
            Log::info("🚗 Processing Vehicle ID: {$vehicle->id} ({$vehicle->name})");

            if ($vehicle->vehicleLifespan <= 0) {
                Log::error("⚠️ Invalid lifespan for Vehicle ID: {$vehicle->id}. Skipping.");
                continue;
            }

            // ✅ Compute Annual Depreciation (Straight-Line Method)
            $purchasePrice = $vehicle->vehicleCost;
            $lifespan = $vehicle->vehicleLifespan;
            $tripTickets = $vehicle->tripTickets ?? collect();
            $totalDistance = $tripTickets->sum('distance');

            $residualValue = match ($vehicle->vehicleType) {
                'light' => $totalDistance * 0.05,
                'medium' => $totalDistance * 0.075,
                'heavy' => $totalDistance * 0.10,
                default => 0, // Default value if type is missing
            };

            $annualDepreciation = ($purchasePrice - $residualValue) / $lifespan;
            Log::info("💰 Annual Depreciation: PHP {$annualDepreciation}");

            // ✅ Get Total Maintenance Cost for the Year
            $currentYear = Carbon::now()->year;
            $maintenanceCost = Maintenance::where('vehicle_id', $vehicle->id)
                ->whereYear('scheduled_date', $currentYear)
                ->sum('amount');

            Log::info("🔧 Total Maintenance Cost for {$currentYear}: PHP {$maintenanceCost}");

            // ✅ Compare Costs & Recommend Action
            $recommendation = $maintenanceCost > $annualDepreciation * 1.5 ? 'replace' : 'maintain';
            Log::info("🔍 Recommendation: {$recommendation} for Vehicle ID {$vehicle->id}");

            // ✅ Store Depreciation & Recommendation Data in Asset Report
            $assetReport = AssetReport::updateOrCreate(
                ['vehicle_id' => $vehicle->id, 'year' => $currentYear],
                [
                    'annual_depreciation' => $annualDepreciation,
                    'maintenance_cost' => $maintenanceCost,
                    'recommendation' => $recommendation,
                ]
            );

            Log::info("📊 Asset depreciation stored for Vehicle ID: {$vehicle->id}");

            // ✅ Generate and store PDF report
            $filename = "asset_report_{$vehicle->id}_{$currentYear}.pdf";
            $folderPath = "asset_reports/{$vehicle->id}/";
            $fullPath = "{$folderPath}{$filename}";

            // ✅ Generate PDF
            $pdf = Pdf::loadView('pdf.assetReport', [
                'assetReport' => $assetReport,
                'vehicle' => $vehicle,
            ]);

            // ✅ Save PDF
            Storage::disk('public')->put($fullPath, $pdf->output());

            Log::info("📄 PDF saved at: storage/app/public/{$fullPath}");
        }

        $this->info("✅ Asset depreciation computed successfully for all vehicles.");
    }
}
