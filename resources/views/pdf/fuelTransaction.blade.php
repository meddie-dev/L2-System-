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
    <div style="margin-left: 15rem;"><h3>FUEL TRANSACTION</h3></div>
    <!-- Driver Information -->
    <hr>
    <div class="section">
      <h2>Driver Information</h2>
      <p>Name: <span style="font-weight: bold;"></span></p>
      <p>License Number: <span style="font-weight: bold;">ABC123</span></p>
      <p>Email: <span style="font-weight: bold;"></span></p>
      <p>Driver Type: <span style="font-weight: bold;"></span></p>
      <p>Contact Number: <span style="font-weight: bold;">+639123456789</span></p>
    </div>

   <!-- Vehicle Information -->
   <hr>
    <div class="section">
      <h2>Vehicle Information</h2>
      <p>Vehicle Type: <span style="font-weight: bold;"></span></p>
      <p>Plate Number: <span style="font-weight: bold;"></span></p>
      <p>Vehicle Model: <span style="font-weight: bold;"></span></p>
      <p>Vehicle Make: <span style="font-weight: bold;"></span></p>
      <p>Vehicle Color: <span style="font-weight: bold;"></span></p>
      <p>Vehicle Year: <span style="font-weight: bold;"></span></p>
      <p>Fuel Type: <span style="font-weight: bold;"></span></p>
      <p>Fuel Efficiency: <span style="font-weight: bold;"> km/l</span></p>
      <p>Vehicle Capacity: <span style="font-weight: bold;"> kg</span></p>
    </div>

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
  </div>

</x-layout.pdfTemplate>