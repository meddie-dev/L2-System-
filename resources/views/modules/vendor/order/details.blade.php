<x-layout.portal.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-sm:justify-center" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : (Auth::user()->hasRole('Vendor') ? 'vendorPortal.dashboard' : 'staff.dashboard'))) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('vendorPortal.order') }}" :active="false" :isLast="false">
        Order Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Review Order Request (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $order->orderNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-overflow-x-auto tw-mb-6">
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Order Information</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">View and track the details of this order.</p>
      </div>
      <table class="datatable tw-min-w-full tw-divide-y tw-divide-gray-200 tw-my-3">
        <thead class="tw-bg-gray-50">
          <tr>
            <th scope="col" class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-tracking-wider">Product Name</th>
            <th scope="col" class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-tracking-wider">Price</th>
            <th scope="col" class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-tracking-wider">Quantity</th>
            <th scope="col" class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-tracking-wider">Amount</th>
          </tr>
        </thead>
        <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200">
          @foreach (json_decode($order->products, true) as $productId => $product)
          <tr>
            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
              {{ $product['name'] }}
            </td>
            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
              {{ $product['price'] }}
            </td>
            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
              {{ $product['quantity'] }}
            </td>
            <td class="tw-px-6 tw-py-4 tw-whitespace-nowrap">
              {{ $product['price'] * $product['quantity'] }}
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-px-4 tw-mt-5 tw-text-sm ">
        <div>
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Order Number:</label>
          <input type="text" id="orderNumber" name="orderNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ strtoupper(Str::random(20)) }}" readonly>
        </div>

        <div>
          <label for="quantity" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Quantity:</label>
          <input type="text" id="quantity" name="quantity" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->quantity }}" readonly>
        </div>
        <div>
          <label for="weight" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Weight:</label>
          <input type="text" id="weight" name="weight" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->weight }}" readonly>
        </div>
        <div>
          <label for="deliveryAddress" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Delivery Address:</label>
          <input type="text" id="deliveryAddress" name="deliveryAddress" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->deliveryAddress }}" readonly>
        </div>
        <div>
          <label for="deliveryRequestDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Delivery Request Date:</label>
          <input type="text" id="deliveryRequestDate" name="deliveryRequestDate" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->deliveryRequestDate }}" readonly>
        </div>
        <div>
          <label for="specialInstructions" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Special Instructions:</label>
          <input type="text" id="specialInstructions" name="specialInstructions" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->specialInstructions ? $order->specialInstructions : 'N/A' }}" readonly>
        </div>
      </div>

      @if ($order->reviewed_by != null && $order->approved_by == null)
      <!-- Reviewed By -->
      <div class="tw-bg-blue-500 tw-rounded-lg tw-px-6 tw-py-4 tw-my-8 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-lg tw-font-bold tw-mb-2 | max-md:tw-text-sm">Reviewed By</h2>
        <p class="tw-text-sm | max-md:tw-text-xs">Track and view the latest details of this order.</p>
      </div>
      <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-gap-2">
        <div>
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Name</label>
          <input type="text" id="approvedByName" name="approvedByName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="APPROVED BY NAME" value="{{ optional(App\Models\User::find($order->reviewed_by))->firstName }} {{ optional(App\Models\User::find($order->reviewed_by))->lastName }}" readonly>
        </div>
        <div>
          <label for="product" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Approved At</label>
          <input type="text" id="product" name="product" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->updated_at->format('Y-m-d') }}" readonly>
        </div>
        <div>
          <label for="deliveryLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Role</label>
          <input type="text" id="deliveryLocation" name="deliveryLocation" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ optional(App\Models\User::find($order->reviewed_by))->roles->pluck('name')->first() ?? 'N/A' }}" readonly>
        </div>

      </div>
      @endif

      @if ($order->approved_by != null)
      <!-- Reviewed By -->
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Reviewed By</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">Track and view the latest details of this order.</p>
      </div>
      <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-gap-2">
        <div>
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Name</label>
          <input type="text" id="approvedByName" name="approvedByName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="APPROVED BY NAME" value="{{ optional(App\Models\User::find($order->reviewed_by))->firstName }} {{ optional(App\Models\User::find($order->reviewed_by))->lastName }}" readonly>
        </div>
        <div>
          <label for="product" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Approved At</label>
          <input type="text" id="product" name="product" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->updated_at->format('Y-m-d') }}" readonly>
        </div>
        <div>
          <label for="deliveryLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Role</label>
          <input type="text" id="deliveryLocation" name="deliveryLocation" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ optional(App\Models\User::find($order->reviewed_by))->roles->pluck('name')->first() ?? 'N/A' }}" readonly>
        </div>
      </div>

      <!-- Approved By -->
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Approved By</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">View and track the details of this approval.</p>
      </div>
      <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-gap-2">
        <div>
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Name</label>
          <input type="text" id="approvedByName" name="approvedByName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="APPROVED BY NAME" value="{{ optional(App\Models\User::find($order->approved_by))->firstName }} {{ optional(App\Models\User::find($order->approved_by))->lastName }}" readonly>
        </div>
        <div>
          <label for="product" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Approved At</label>
          <input type="text" id="product" name="product" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->updated_at->format('Y-m-d') }}" readonly>
        </div>
        <div>
          <label for="deliveryLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Role</label>
          <input type="text" id="deliveryLocation" name="deliveryLocation" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ optional(App\Models\User::find($order->approved_by))->roles->pluck('name')->first() ?? 'N/A' }}" readonly>
        </div>
      </div>
      @endif

    </div>
    <div>
      <div class="tw-flex tw-items-center tw-justify-start tw-my-6">
        <a href="{{ route('vendorPortal.order') }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-p-3 ">
          <i class="fa-solid fa-arrow-left tw-mr-2 | max-md:tw-text-xs"></i>
          Back
        </a>
      </div>
    </div>
    <hr>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2">Order Table Instructions</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2">
        <p class="tw-mb-1">In the table above, you can view and manage your order requests created by you by clicking on the "Order No." column of the table. When you click on the "Order No.", you will be redirected to the order details page. </p>
        <p class="tw-mt-2">To add a new order request, click the "Add New" button.</p>
      </div>
    </div>
  </div>

</x-layout.portal.mainTemplate>