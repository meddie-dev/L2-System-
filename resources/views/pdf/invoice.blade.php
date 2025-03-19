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
    <div style="margin-left: 15rem;"><h3>INVOICE APPROVAL</h3></div>
    <!-- Vendor Information -->
    <hr>
    <div class="section">
      <h2>Vendor Information</h2>
      <p>Name: <span style="font-weight: bold;">{{ (\App\Models\User::find($order->user_id))->firstName }} {{ (\App\Models\User::find($order->user_id))->lastName }}</span></p>
      <p>Email: <span style="font-weight: bold;">{{ (\App\Models\User::find($order->user_id))->email }}</span></p>
      <p>Contact Number: <span style="font-weight: bold;">+639123456789</span></p>
    </div>

    <!-- Order Information -->
    <hr>
    <h2 style="margin-bottom: 13px;">Order Information</h2>
    <table class="table">
      <tr>
        <th>Key</th>
        <th>Information</th>
      </tr>
      <tr>
        <td>Order Number</td>
        <td>{{ $order->orderNumber }}</td>
      </tr>
      <tr>
        <td>Quantity</td>
        <td>{{ $order->quantity }}</td>
      </tr>
      <tr>
        <td>Weight</td>
        <td>{{ $order->weight }} kg</td>
      </tr>
      <tr>
        <td>Amount</td>
        <td>PHP {{ number_format($order->amount, 2) }}</td>
      </tr>
      <tr>
        <td>Delivery Address</td>
        <td>{{ $order->deliveryAddress }}</td>
      </tr>
      <tr>
        <td>Delivery Request Date</td>
        <td>{{ $order->deliveryRequestDate }}</td>
      </tr>
      <tr>
        <td>Special Instructions</td>
        <td>{{ $order->specialInstructions ? $order->specialInstructions : '-' }}</td>
      </tr>
      <tr>
        <td>Approval Status</td>
        <td>{{ ucfirst($order->approval_status) }}</td>
      </tr>
    </table>

    <!-- Product Information -->
    <hr>
    <h2 style="margin-bottom: 13px;">Product Information</h2>
    @php
    $products = json_decode($order->products, true);
    @endphp
    <table class="table">
      <tr>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Weight</th>
      </tr>
      @foreach ($products as $product)
      <tr>
        <td>{{ $product['name'] }}</td>
        <td>{{ $product['quantity'] }}</td>
        <td>PHP {{ number_format($product['price'], 2) }}</td>
        <td>{{ number_format($product['weight'] * $product['quantity'], 2) }} kg</td>
      </tr>
      @endforeach
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
        <td>{{ $order->payment->paymentNumber }}</td>
      </tr>
      <tr>
        <td>Date</td>
        <td>{{ $order->payment->date }}</td>
      </tr>
      <tr>
        <td>Due Date</td>
        <td>{{ $order->payment->due_date }}</td>
      </tr>
      <tr>
        <td>Account Number</td>
        <td>{{ $order->payment->account_number }}</td>
      </tr>
      <tr>
        <td>Total Amount Due</td>
        <td>PHP {{ number_format($order->payment->total_amount_due, 2) }}</td>
      </tr>
      <tr>
        <td>Approval Status</td>
        <td>{{ ucfirst($order->payment->approval_status) }}</td>
      </tr>
      <tr>
        <td>Payment Terms</td>
        <td>{{ \Carbon\Carbon::parse($order->payment->date)->diffInDays(\Carbon\Carbon::parse($order->payment->due_date)) }} days</td>
      </tr>
    </table>
  </div>

</x-layout.pdfTemplate>