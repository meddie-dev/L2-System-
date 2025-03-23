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
    <div style="margin-left: 15rem;"><h3>VEHICLE BOOKING</h3></div>
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
        <td>{{ $vehicleReservation->reviewed_by ? \App\Models\User::find($vehicleReservation->reviewed_by)->firstName . ' ' . \App\Models\User::find($vehicleReservation->reviewed_by)->lastName : '-' }}</td>
      </tr>
      <tr>
        <td>Approved By</td>
        <td>{{ $vehicleReservation->approved_by ? \App\Models\User::find($vehicleReservation->approved_by)->firstName . ' ' . \App\Models\User::find($vehicleReservation->approved_by)->lastName : '-' }}</td>
      </tr>
    </table>

    <!-- Payment Information -->
    <hr>
    <h2 style="margin-bottom: 13px;">Payment Information</h2>
    <table class="table">
      <tr>
        <th>Key</th>
        <th>Information</th>
      </tr>
      <tr>
        <td>Payment Number</td>
        <td>{{ $vehicleReservation->payment->paymentNumber }}</td>
      </tr>
      <tr>
        <td>Date</td>
        <td>{{ $vehicleReservation->payment->date }}</td>
      </tr>
      <tr>
        <td>Due Date</td>
        <td>{{ $vehicleReservation->payment->due_date }}</td>
      </tr>
      <tr>
        <td>Account Number</td>
        <td>{{ $vehicleReservation->payment->account_number }}</td>
      </tr>
      <tr>
        <td>Total Amount Due</td>
        <td>PHP {{ number_format($vehicleReservation->payment->total_amount_due, 2) }}</td>
      </tr>
      <tr>
        <td>Approval Status</td>
        <td>{{ ucfirst($vehicleReservation->payment->approval_status) }}</td>
      </tr>
      <tr>
        <td>Payment Terms</td>
        <td>{{ \Carbon\Carbon::parse($vehicleReservation->payment->date)->diffInDays(\Carbon\Carbon::parse($vehicleReservation->payment->due_date)) }} days</td>
      </tr>
    </table>
  </div>

</x-layout.pdfTemplate>