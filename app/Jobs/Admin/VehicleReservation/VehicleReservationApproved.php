<?php

namespace App\Jobs\Admin\VehicleReservation;

use App\Models\ActivityLogs;
use App\Models\TripTicket;
use App\Models\FleetCard;
use App\Models\Fuel;
use App\Models\Modules\VehicleReservation;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VehicleReservationApproved implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $vehicleReservation;

    /**
     * Create a new job instance.
     */
    public function __construct(VehicleReservation $vehicleReservation)
    {
        $this->vehicleReservation = $vehicleReservation;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $reservation = $this->vehicleReservation;
        $apiKey = env('OPENROUTESERVICE_API_KEY');
        $fuelCostPerLitre = env('FUEL_COST_PER_LITRE', 56.55);
        $client = new Client();

        try {
            Log::info("The reservation ID is: " . $reservation->id);
            // 0️⃣ Find the admin user
            $admin = User::role('Admin')->first();

            if (!$admin) {
                Log::error("Admin user not found.");
                return;
            }

            // 1️⃣ Find an available driver
            $driver = User::role('Driver')
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'Driver');
                })
                ->where('driverType', $reservation->vehicle_type)
                ->where('status', 'available')
                ->orderByRaw('CASE WHEN performance_score > 60 THEN 1 ELSE 2 END, last_active_at DESC')
                ->first();

            if (!$driver) {
                Log::error("No available driver found.");
                return;
            }

            // 2️⃣ Assign the driver and approve the reservation
            $reservation->update([
                'approval_status' => 'approved',
                'vehicle_id' => $reservation->vehicle_id,
                'approved_by' => $admin->id,
                'redirected_to' => $driver->id,
            ]);

            $driver->update([
                'status' => 'scheduled',
            ]);

            ActivityLogs::create([
                'user_id' => $admin->id,
                'event' => "Approved Vehicle Reservation: {$reservation->reservationNumber} at " . now('Asia/Manila')->format('Y-m-d h:i A'),
                'ip_address' => request()->ip(),
            ]);

            Log::info(" Fetching pickup coordinates for: " . $reservation->pickUpLocation);

            // 3️⃣ Get Coordinates
            $pickUpResponse = $client->get("https://api.openrouteservice.org/geocode/search", [
                'query' => ['api_key' => $apiKey, 'text' => $reservation->pickUpLocation],
            ]);
            $dropOffResponse = $client->get("https://api.openrouteservice.org/geocode/search", [
                'query' => ['api_key' => $apiKey, 'text' => $reservation->dropOffLocation],
            ]);

            $pickUpData = json_decode($pickUpResponse->getBody(), true);
            $dropOffData = json_decode($dropOffResponse->getBody(), true);

            if (empty($pickUpData['features']) || empty($dropOffData['features'])) {
                Log::error("Geocode API failed: No coordinates found.");
                return;
            }

            $pickUpCoords = $pickUpData['features'][0]['geometry']['coordinates'];
            $dropOffCoords = $dropOffData['features'][0]['geometry']['coordinates'];

            Log::info(" Coordinates received: Pickup ({$pickUpCoords[1]}, {$pickUpCoords[0]}), Dropoff ({$dropOffCoords[1]}, {$dropOffCoords[0]})");

            // 4️⃣ Get Distance & Duration
            Log::info(" Fetching distance & duration");

            $request = new \GuzzleHttp\Psr7\Request('POST', 'https://api.openrouteservice.org/v2/matrix/driving-car', [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $apiKey,
            ], json_encode([
                'locations' => [[$pickUpCoords[0], $pickUpCoords[1]], [$dropOffCoords[0], $dropOffCoords[1]]],
                'metrics' => ['distance', 'duration']
            ]));

            $response = $client->send($request);
            $data = json_decode($response->getBody(), true);

            if (empty($data['distances'][0][1]) || empty($data['durations'][0][1])) {
                Log::error("Distance matrix API failed.");
                return;
            }

            $distance = $data['distances'][0][1] / 1000; // km
            $duration = $data['durations'][0][1] / 60;   // minutes

            Log::info(" Distance: {$distance} km, Duration: {$duration} mins");

            // 5️⃣ Get vehicle details
            Log::info(" Fetching vehicle data for ID: " . $reservation->vehicle_id);

            $vehicle = Vehicle::find($reservation->vehicle_id);
            if (!$vehicle || !$vehicle->fuel_efficiency || $vehicle->fuel_efficiency <= 0) {
                Log::error("Invalid vehicle data: Missing or zero fuel efficiency.");
                return;
            }

            $fuelEfficiency = $vehicle->fuel_efficiency;
            $estimatedFuelConsumption = ($distance / $fuelEfficiency) * 2;
            $estimatedCost = $estimatedFuelConsumption * $fuelCostPerLitre;
            $creditLimit = max($estimatedCost * 1.05, 5000);

            Log::info(" Vehicle found: {$vehicle->name} - Fuel Efficiency: {$fuelEfficiency} km/L");

            // 6️⃣ Create Fleet Card
            Log::info(" Creating Fleet Card");

            $fleetCard = FleetCard::create([
                'user_id' => $reservation->redirected_to,
                'cardNumber' => Str::upper(Str::random(20)),
                'status' => 'active',
                'credit_limit' => $creditLimit,
            ]);

            Log::info(" Fleet Card created: {$fleetCard->cardNumber} with limit ₱{$creditLimit}");

            // 7️⃣ Create Trip Ticket
            Log::info(" Creating Trip Ticket");

            $tripTicket = TripTicket::create([
                'user_id' => $reservation->redirected_to,
                'vehicle_reservation_id' => $reservation->id,
                'vehicle_id' => $reservation->vehicle_id,
                'fleet_card_id' => $fleetCard->id,
                'tripNumber' => Str::upper(Str::random(20)),
                'status' => 'scheduled',
                'destination' => $reservation->dropOffLocation,
                'departureTime' => $reservation->reservationDate . ' ' . $reservation->reservationTime,
                'arrivalTime' => Carbon::parse($reservation->reservationDate . ' ' . $reservation->reservationTime)->addMinutes($duration + 30),
                'allocatedFuel' => $creditLimit,
                'distance' => $distance,
                'duration' => $duration,
                'pickUpLat' => $pickUpCoords[1],
                'pickUpLng' => $pickUpCoords[0],
                'dropOffLat' => $dropOffCoords[1],
                'dropOffLng' => $dropOffCoords[0],
                'approved_by' => $admin->id,

            ]);

            Log::info(" Trip Ticket created: {$tripTicket->tripNumber}");

            // Create Fuel entry
            Fuel::create([
                'fuelNumber' => Str::upper(Str::random(20)),
                'trip_ticket_id' => $tripTicket->id,
                'fleet_card_id' => $fleetCard->id,
                'fuelType' => $vehicle->vehicleFuelType,
                'vehicle_id' => $reservation->vehicle_id,
                'estimatedFuelConsumption' => $estimatedFuelConsumption,
                'estimatedCost' => $estimatedCost,
                'fuelDate' => Carbon::parse($tripTicket->departureTime)->startOfDay()->isToday()
                    ? Carbon::parse($tripTicket->departureTime)->subHours(2)
                    : Carbon::parse($tripTicket->departureTime)->subDays(2),

                'updated_at' => now(),
            ]);

            

            // 8️⃣ Generate PDF and Save
            Log::info(" Generating PDF");

            $pdf = Pdf::loadView('pdf.reservationTripTicket', [
                'vehicleReservation' => $reservation,
                'tripTicket' => $tripTicket,
                'fuel' => Fuel::where('trip_ticket_id', $tripTicket->id)->first(),
            ]);

            $filename = $tripTicket->tripNumber . '.pdf';
            $folderPath = "trip_tickets/{$reservation->redirected_to}/";
            $fullPath = "{$folderPath}{$filename}";

            Storage::disk('public')->put($fullPath, $pdf->output());
            Log::info(" PDF saved at: " . storage_path("app/public/{$folderPath}{$filename}"));
        } catch (\Exception $e) {
            Log::error("Error processing vehicle reservation: " . $e->getMessage());
        }
    }
}
