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
    <div style="margin-left: 15rem;"><h3>MAINTENANCE REPORT</h3></div>
    
    <!-- Vehicle Information -->
    <hr>
    <div class="section">
      <h2>Vehicle Information</h2>
      <p>Vehicle Type: <span style="font-weight: bold;">{{$vehicle->vehicleType === 'light' ? 'Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)' : ($vehicle->vehicleType === 'medium' ? 'Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)' : ($vehicle->vehicleType === 'heavy' ? 'Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)' : 'N/A')) }}</span></p>
      <p>Plate Number: <span style="font-weight: bold;">{{$vehicle->plateNumber }}</span></p>
      <p>Vehicle Model: <span style="font-weight: bold;">{{$vehicle->vehicleModel }}</span></p>
      <p>Vehicle Make: <span style="font-weight: bold;">{{$vehicle->vehicleMake }}</span></p>
      <p>Vehicle Color: <span style="font-weight: bold;">{{$vehicle->vehicleColor }}</span></p>
      <p>Vehicle Year: <span style="font-weight: bold;">{{$vehicle->vehicleYear }}</span></p>
      <p>Fuel Type: <span style="font-weight: bold;">{{ ucfirst($vehicle->vehicleFuelType) }}</span></p>
      <p>Fuel Efficiency: <span style="font-weight: bold;">{{$vehicle->fuel_efficiency }} km/l</span></p>
      <p>Vehicle Capacity: <span style="font-weight: bold;">{{$vehicle->vehicleCapacity }} kg</span></p>
    </div>

    <hr>
    <h2 style="margin-bottom: 13px;">Maintenance Information</h2>
    <table class="table">
      <tr>
        <th>Key</th>
        <th>Information</th>
      </tr>
      <tr>
        <td>Maintenance Task</td>
        <td>{{ ucfirst($maintenance->task) }}</td>
      </tr>
      <tr>
        <td>Maintenance Number</td>
        <td>{{ $maintenance->maintenanceNumber }}</td>
      </tr>
      <tr>
        <td>Maintenance Amount</td>
        <td>PHP {{ number_format($maintenance->amount, 2) }}</td>
      </tr>
      <tr>
        <td>Maintenance Scheduled Date</td>
        <td>{{ \Carbon\Carbon::parse($maintenance->scheduled_date)->format('F d, Y') }}</td>
      </tr>
      <tr>
        <td>Maintenance Completed Date</td>
        <td>{{ $maintenance->completed_date ? \Carbon\Carbon::parse($maintenance->completed_date)->format('F d, Y') : 'N/A' }}</td>
      </tr>
      <tr>
        <td>Maintenance Condition Status</td>
        <td>{{ ucfirst($maintenance->conditionStatus) }}</td>
      </tr>
    </table>
  </div>

</x-layout.pdfTemplate>