<?php

namespace App\Listeners;

use App\Events\VehicleReservationApproved;
use App\Models\FleetCard;
use App\Models\Fuel;
use App\Models\TripTicket;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateTripTicketOnApproval
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VehicleReservationApproved $event)
    {
        $reservation = $event->vehicleReservation;
        $apiKey = env('OPENROUTESERVICE_API_KEY');
        $fuelCostPerLitre = env('FUEL_COST_PER_LITRE', 56.55); // Get from .env or config
        $client = new Client();

        try {
            // Get Coordinates
            $pickUpResponse = $client->get("https://api.openrouteservice.org/geocode/search", [
                'query' => ['api_key' => $apiKey, 'text' => $reservation->pickUpLocation],
            ]);
            $dropOffResponse = $client->get("https://api.openrouteservice.org/geocode/search", [
                'query' => ['api_key' => $apiKey, 'text' => $reservation->dropOffLocation],
            ]);

            $pickUpData = json_decode($pickUpResponse->getBody(), true);
            $dropOffData = json_decode($dropOffResponse->getBody(), true);

            if (empty($pickUpData['features']) || empty($dropOffData['features'])) {
                Log::error("Geocode API failed: No features found for locations.");
                return;
            }

            $pickUpCoords = $pickUpData['features'][0]['geometry']['coordinates'];
            $dropOffCoords = $dropOffData['features'][0]['geometry']['coordinates'];

            // Get Distance and Duration
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
            Log::info("Matrix API Response", ['response' => $data]);

            if (empty($data['distances'][0][1]) || empty($data['durations'][0][1])) {
                Log::error("Matrix API failed: No valid distance or duration returned.");
                return;
            }

            $distance = $data['distances'][0][1] / 1000;
            $duration = $data['durations'][0][1] / 60;

            // Get vehicle details
            $vehicle = Vehicle::find($reservation->vehicle_id);
            if (!$vehicle || !$vehicle->fuel_efficiency || $vehicle->fuel_efficiency <= 0) {
                Log::error("Invalid vehicle data: Missing or zero fuel efficiency.");
                return;
            }

            $fuelEfficiency = $vehicle->fuel_efficiency;
            $estimatedFuelConsumption = ($distance / $fuelEfficiency) * 2;
            $estimatedCost = $estimatedFuelConsumption * $fuelCostPerLitre;
            $creditLimit = max($estimatedCost * 1.05, 5000);

            // Create Fleet Card
            $fleetCard = FleetCard::create([
                'user_id' => $reservation->redirected_to,
                'cardNumber' => Str::upper(Str::random(12)),
                'status' => 'active',
                'credit_limit' => $creditLimit,
            ]);

            // Create a trip ticket entry with the correct fleet_card_id
            $tripTicket = TripTicket::create([
                'user_id' => $reservation->redirected_to,
                'order_id' => $reservation->order_id,
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
                'rating' => null,
                'feedback' => null,
                'approved_by' => auth()->user()->id,
            ]);

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

            // Define the filename and storage path
            $filename = $tripTicket->tripNumber . '.pdf';
            $folderPath = "trip_tickets/{$reservation->redirected_to}/";
            $fullPath = "{$folderPath}{$filename}";

            // Ensure directory exists
            Storage::disk('public')->makeDirectory($folderPath);

            // Check if an existing PDF file needs to be deleted
            if (Storage::disk('public')->exists($fullPath)) {
                Storage::disk('public')->delete($fullPath);
            }

            // Generate PDF
            $pdf = Pdf::loadView('pdf.tripTicket', [
                'vehicleReservation' => $reservation,
                'tripTicket' => $tripTicket,
                'fuel' => Fuel::where('trip_ticket_id', $tripTicket->id)->first(),
            ]);

            // Store PDF in public disk
            Storage::disk('public')->put($fullPath, $pdf->output());

            // Log for debugging
            Log::info("PDF saved at: " . storage_path("app/public/{$folderPath}{$filename}"));

            Log::info("Fleet Card Created", ['credit_limit' => $creditLimit]);
        } catch (\Exception $e) {
            Log::error("Error in handling reservation: " . $e->getMessage());
        }
    }
}
