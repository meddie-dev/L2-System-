<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-sm:justify-center" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('staff.vendors.manage') }}" :active="false" :isLast="false">
        Manage Request Management
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
      <h3 class="tw-text-md tw-font-bold tw-my-4">Vendor Information</h3>
      <div class="tw-px-4 tw-flex tw-flex-col tw-gap-2 tw-text-sm ">
        <div>
          <p class="tw-font-semibold">Vendor ID: (<span class="tw-text-gray-700 tw-font-normal">{{ $order->user->id }}</span>)</p>
        </div>
        <div>
          <p class="tw-font-semibold ">Vendor Name: (<span class="tw-text-gray-700 tw-font-normal">{{ $order->user->firstName }} {{ $order->user->lastName }}</span>)</p>
        </div>
        <div>
          <p class="tw-font-semibold">Email: (<span class="tw-text-gray-700 tw-font-normal">{{ $order->user->email }}</span>)</p>
        </div>
        <div>
          <p class=" tw-font-semibold">Verification Status: (<span class="tw-text-gray-700 tw-font-normal">{{ $order->user->two_factor_enabled ? 'Verified' : 'Not Verified' }}</span>)</p>
        </div>
      </div>
      <h3 class="tw-text-md tw-font-bold tw-my-4">Order Information</h3>
      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-px-4 tw-text-sm">
        <div>
          <p class=" tw-font-semibold">Order Number:</p>
          <p class="tw-text-gray-700">{{ $order->orderNumber }}</p>
        </div>
        <div>
          <p class=" tw-font-semibold">Pickup Location:</p>
          <p class="tw-text-gray-700">{{ $order->pickupLocation }}</p>
        </div>
        <div>
          <p class=" tw-font-semibold">Delivery Location:</p>
          <p class="tw-text-gray-700">{{ $order->deliveryLocation }}</p>
        </div>
        <div>
          <p class=" tw-font-semibold">Package Weight:</p>
          <p class="tw-text-gray-700"> {{ $order->packageWeight }} kg ({{ $order->packageWeight * 2.20462 }} lbs)</p>
        </div>
        <div>
          <p class=" tw-font-semibold">Document Upload:</p>
          <p class="tw-text-gray-700">{{ $order->documentUpload ? $order->documentUpload : 'N/A' }}</p>
        </div>
        <div>
          <p class=" tw-font-semibold">Created At:</p>
          <p class="tw-text-gray-700">{{ $order->created_at->format('M d, Y') }}</p>
        </div>
        <div>
          <p class=" tw-font-semibold">Status:</p>
          <p class="tw-text-gray-700">{{ ucfirst($order->approval_status) }}</p>
        </div>
        <div>
          <p class=" tw-font-semibold">Order Details/Descriptions:</p>
          <p class="tw-text-gray-700">{{ $order->specialInstructions ? $order->specialInstructions : 'N/A' }}</p>
        </div>
        <div>
          <form action="{{ route('staff.vendors.approve', $order->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="tw-text-white tw-bg-blue-700 tw-font-bold tw-py-2 tw-px-4 tw-rounded | max-sm:tw-text-sm">Approve Order</button>
          </form>
        </div>
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

</x-layout.mainTemplate>