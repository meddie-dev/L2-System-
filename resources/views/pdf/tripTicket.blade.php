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
    <div class="section">
      <h2>Driver Information</h2>
      <p>Name: <span style="font-weight: bold;">{{ (\App\Models\User::find($vehicleReservation->redirected_to))->firstName }} {{ (\App\Models\User::find($vehicleReservation->redirected_to))->lastName }}</span></p>
      <p>License Number: <span style="font-weight: bold;">ABC123</span></p>
      <p>Email: <span style="font-weight: bold;">{{ (\App\Models\User::find($vehicleReservation->redirected_to))->email }}</span></p>
      <p>Driver Type: <span style="font-weight: bold;">{{ (\App\Models\User::find($vehicleReservation->redirected_to))->driverType === 'light' ? 'Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)' : ((\App\Models\User::find($vehicleReservation->redirected_to))->driverType === 'medium' ? 'Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)' : ((\App\Models\User::find($vehicleReservation->redirected_to))->driverType === 'heavy' ? 'Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)' : 'N/A')) }}</span></p>
      <p>Contact Number: <span style="font-weight: bold;">+639123456789</span></p>
    </div>

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
      <p>Vehicle Capacity: <span style="font-weight: bold;">{{ $vehicleReservation->vehicle->vehicleCapacity }} kg</span></p>
    </div>

    <hr>
    <h2 style="margin-bottom: 13px;">Trip Information</h2>
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
        <td>{{ ucfirst($vehicleReservation->vehicle_type) }}</td>
      </tr>
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

    <hr>
    <h2 style="margin-bottom: 13px;">Fuel Information</h2>
    <table class="table">
      <tr>
        <th>Description</th>
        <th>Value</th>
      </tr>
      <tr>
        <td>Credit Card Number</td>
        <td>{{ $fleetCard = \App\Models\FleetCard::find($vehicleReservation->fleet_card_id) ? 'XXXX-XXXX-XXXX-' . substr($fleetCard->cardNumber, -4) : '' }}</td>
      </tr>
      <tr>
        <td>Credit Limit</td>
        <td>{{ $fleetCard = \App\Models\FleetCard::find($vehicleReservation->fleet_card_id) ? number_format($fleetCard->credit_limit, 2) : '' }}</td>
      </tr>
      <tr>
        <td>Balance</td>
        <td>{{ $fleetCard = \App\Models\FleetCard::find($vehicleReservation->fleet_card_id) ? number_format($fleetCard->balance, 2) : '' }}</td>
      </tr>
      <tr>
        <td>Status</td>
        <td>{{ $fleetCard = \App\Models\FleetCard::find($vehicleReservation->fleet_card_id) ? ucfirst($fleetCard->status) : '' }}</td>
      </tr>
      <tr>
        <td>Expiry Date</td>
        <td>{{ $fleetCard = \App\Models\FleetCard::find($vehicleReservation->fleet_card_id) ? \Carbon\Carbon::parse($fleetCard->expiry_date)->format('F Y') : '' }}</td>
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