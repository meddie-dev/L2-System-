<x-layout.pdfTemplate>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 14px;
      margin: 20px;
    }

    .container {
      width: 100%;
      padding: 20px;
    }

    .section {
      margin-bottom: 20px;
    }

    h2 {
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 5px;
    }

    p {
      margin-bottom: 5px;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table th,
    .table td {
      border: 1px solid #ddd;
      padding: 8px;
    }

    .table th {
      background-color: #333;
      color: white;
      text-align: left;
    }

    .table tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .flex-container {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      margin-top: 40px;
    }

    .signature {
      text-align: right;
      margin-top: 20px;
    }

    .signature p {
      margin-bottom: 5px;
    }

    .signature hr {
      border: none;
      border-top: 1px solid #333;
      margin: 10px 0;
      width: 27%;
      margin-left: auto;
    }
  </style>
  <div class="container">
  <hr>
  <div style="margin-left: 11rem;"><h3>TRIP TICKET INFORMATION (BOOKING)</h3></div>
    <!-- Driver Information -->
    <hr>
    <div class="section">
      <h2>Driver Information</h2>
      <p>Name: <span style="font-weight: bold;">{{ (\App\Models\User::find($vehicleReservation->redirected_to))->firstName }} {{ (\App\Models\User::find($vehicleReservation->redirected_to))->lastName }}</span></p>
      <p>License Number: <span style="font-weight: bold;">ABC123</span></p>
      <p>Email: <span style="font-weight: bold;">{{ (\App\Models\User::find($vehicleReservation->redirected_to))->email }}</span></p>
      <p>Driver Type: <span style="font-weight: bold;">{{ (\App\Models\User::find($vehicleReservation->redirected_to))->driverType === 'light' ? 'Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)' : ((\App\Models\User::find($vehicleReservation->redirected_to))->driverType === 'medium' ? 'Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)' : ((\App\Models\User::find($vehicleReservation->redirected_to))->driverType === 'heavy' ? 'Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)' : 'N/A')) }}</span></p>
      <p>Contact Number: <span style="font-weight: bold;">+639123456789</span></p>
    </div>

    <!-- Vehicle Information -->
    <hr>
    <div class="section">
      <h2>Vehicle Information</h2>
      <p>Vehicle Type: <span style="font-weight: bold;">{{ $vehicleReservation->vehicle->vehicleType === 'light' ? 'Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)' : ($vehicleReservation->vehicle->vehicleType === 'medium' ? 'Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)' : ($vehicleReservation->vehicle->vehicleType === 'heavy' ? 'Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)' : 'N/A')) }}</span></p>
      <p>Plate Number: <span style="font-weight: bold;">{{ $vehicleReservation->vehicle->plateNumber }}</span></p>
      <p>Vehicle Model: <span style="font-weight: bold;">{{ $vehicleReservation->vehicle->vehicleModel }}</span></p>
      <p>Vehicle Make: <span style="font-weight: bold;">{{ $vehicleReservation->vehicle->vehicleMake }}</span></p>
      <p>Vehicle Color: <span style="font-weight: bold;">{{ $vehicleReservation->vehicle->vehicleColor }}</span></p>
      <p>Vehicle Year: <span style="font-weight: bold;">{{ $vehicleReservation->vehicle->vehicleYear }}</span></p>
      <p>Fuel Type: <span style="font-weight: bold;">{{ ucfirst($vehicleReservation->vehicle->vehicleFuelType) }}</span></p>
      <p>Fuel Efficiency: <span style="font-weight: bold;">{{ $vehicleReservation->vehicle->fuel_efficiency }} km/l</span></p>
      <p>Vehicle Capacity: <span style="font-weight: bold;">{{ $vehicleReservation->vehicle->vehicleCapacity }} kg</span></p>
    </div>

    <!-- Reservation Information -->
    <hr>
    <h2 style="margin-bottom: 13px;">Reservation Information</h2>
    <table class="table">
      <tr>
        <th>Key</th>
        <th>Information</th>
      </tr>
      <tr>
        <td>Reservation Number</td>
        <td>{{ $vehicleReservation->reservationNumber }}</td>
      </tr>
      <tr>
        <td>Vehicle Type</td>
        <td>{{ $vehicleReservation->vehicle->vehicleType === 'light' ? 'Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)' : ($vehicleReservation->vehicle->vehicleType === 'medium' ? 'Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)' : ($vehicleReservation->vehicle->vehicleType === 'heavy' ? 'Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)' : 'N/A')) }}</td>
      <tr>
        <td>Reservation Date</td>
        <td>{{ $vehicleReservation->reservationDate }}</td>
      </tr>
      <tr>
        <td>Reservation Time</td>
        <td>{{ $vehicleReservation->reservationTime }}</td>
      </tr>
      <tr>
        <td>Pick Up Location</td>
        <td>{{ $vehicleReservation->pickUpLocation }}</td>
      </tr>
      <tr>
        <td>Drop Off Location</td>
        <td>{{ $vehicleReservation->dropOffLocation }}</td>
      </tr>
      <tr>
        <td>Approval Status</td>
        <td>{{ ucfirst($vehicleReservation->approval_status) }}</td>
      </tr>
      <tr>
        <td>Reviewed By</td>
        <td>{{ \App\Models\User::find($vehicleReservation->reviewed_by)->firstName . ' ' . \App\Models\User::find($vehicleReservation->reviewed_by)->lastName }}</td>
      </tr>
      <tr>
        <td>Approved By</td>
        <td>{{ \App\Models\User::find($vehicleReservation->approved_by)->firstName . ' ' . \App\Models\User::find($vehicleReservation->approved_by)->lastName }}</td>
      </tr>
    </table>

    <!-- Trip Information -->
    <hr>
    <h2 style="margin-bottom: 13px;">Trip Information</h2>
    <table class="table">
      <tr>
        <th>Key</th>
        <th>Information</th>
      </tr>
      <tr>
        <td>Trip Number</td>
        <td>{{ $tripTicket->tripNumber }}</td>
      </tr>
      <tr>
        <td>Destination</td>
        <td>{{ $tripTicket->destination }}</td>
      </tr>
      <tr>
        <td>Departure Time</td>
        <td>{{ \Carbon\Carbon::parse($tripTicket->departureTime)->format('g:i A') }}</td>
      </tr>
      <tr>
        <td>Arrival Time</td>
        <td>{{ \Carbon\Carbon::parse($tripTicket->arrivalTime)->format('g:i A') }}</td>
      </tr>
      <tr>
        <td>Allocated Fuel</td>
        <td>PHP {{ number_format($tripTicket->allocatedFuel, 2) }}</td>
      </tr>
      <tr>
        <td>Status</td>
        <td>{{ ucfirst($tripTicket->status) }}</td>
      </tr>
      <tr>
        <td>Distance</td>
        <td>{{ number_format($tripTicket->distance, 3) }} km</td>
      </tr>
      <tr>
        <td>Duration</td>
        @php
        $totalMinutes = $tripTicket->duration;
        $hours = floor($totalMinutes / 60);
        $minutes = floor($totalMinutes % 60);
        $seconds = round(($totalMinutes - floor($totalMinutes)) * 60);
        @endphp
        <td>{{ sprintf('%d hour%s %d minute%s %d second%s', $hours, $hours == 1 ? '' : 's', $minutes, $minutes == 1 ? '' : 's', $seconds, $seconds == 1 ? '' : 's') }}</td>
      </tr>

    </table>

    <hr>
    <h2 style="margin-bottom: 13px;">Fuel Information</h2>
    <table class="table">
      <tr>
        <th>Key</th>
        <th>Information</th>
      </tr>
      <tr>
        <td>Fuel Number</td>
        <td>{{ $fuel->fuelNumber }}</td>
      </tr>
      <tr>
        <td>Fuel Type</td>
        <td>{{ ucfirst($fuel->fuelType) }}</td>
      </tr>
      <tr>
        <td>Estimated Fuel Consumption</td>
        <td>{{ number_format($fuel->estimatedFuelConsumption, 2) . ' L' }}</td>
      </tr>
      <tr>
        <td>Estimated Cost</td>
        <td>PHP {{ number_format($fuel->estimatedCost, 2) }}</td>
      </tr>
      <tr>
        <td>Gas Station</td>
        <td>Phoenix Petroleum Philippines, Inc.</td>
      </tr>
      <tr>
        <td>Fuel Date Schedule</td>
        <td>{{ \Carbon\Carbon::parse($fuel->fuelDate)->format('F d, Y') }}</td>
      </tr>
    </table>

    <div class="flex-container">
      <div class="signature">
        <img style="width: 150px; height: 80px; margin-left: auto; margin-bottom: -30px;" src="{{ public_path('img/signature.png') }}" alt="Signature">
        <p style="margin-right: 30px;">{{ strtoupper((\App\Models\User::find($vehicleReservation->approved_by))->firstName) }} {{ strtoupper((\App\Models\User::find($vehicleReservation->approved_by))->lastName) }}</p>
        <hr>
        <p style="margin-right: 20px;">Approved by (Admin)</p>
      </div>
    </div>
  </div>

</x-layout.pdfTemplate>