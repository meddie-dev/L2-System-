<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white " href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon "><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('staff.payment.manage') }}" :active="false" :isLast="false">
        Payment
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Review Payment No.(<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $order->payment->first()->paymentNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-overflow-x-auto tw-mb-6">
      <h3 class="tw-text-md tw-font-bold tw-my-4 tw-text-gray-400 | max-md:tw-text-sm">Vendor Information</h3>
      <div class="tw-px-4 tw-flex tw-flex-col tw-gap-2 tw-text-sm | max-md:tw-text-xs">
        <div>
          <label for="vendorID" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vendor ID</label>
          <input type="text" id="vendorID" name="vendorID" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->user ? $order->user->id : 'N/A' }}" readonly>
        </div>
        <div>
          <label for="vendorName" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vendor Name</label>
          <input type="text" id="vendorName" name="vendorName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->user ? $order->user->firstName . ' ' . $order->user->lastName : 'N/A' }}" readonly>
        </div>
        <div>
          <label for="vendorEmail" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Email</label>
          <input type="text" id="vendorEmail" name="vendorEmail" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->user ? $order->user->email : 'N/A' }}" readonly>
        </div>
        <div>
          <label for="verificationStatus" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Verification Status</label>
          <input type="text" id="verificationStatus" name="verificationStatus" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->user ? ($order->user->two_factor_enabled ? 'Verified' : 'Not Verified') : 'N/A' }}" readonly>
        </div>
      </div>

      <!-- Custom Divider -->
      <div class="tw-flex tw-items-center tw-justify-center tw-mt-4">
        <div class="tw-mx-auto tw-my-6 tw-w-[30%] tw-h-[2px] tw-bg-slate-100"></div>
        <span class="tw-text-sm tw-font-bold tw-text-gray-200 | max-md:tw-text-xs">DS GLOBAL HOLDING INC.</span>
        <div class="tw-mx-auto tw-my-6 tw-w-[30%] tw-h-[2px] tw-bg-slate-100"></div>
      </div>

      <h3 class="tw-text-md tw-font-bold tw-my-4 tw-text-gray-400 | max-md:tw-text-sm">Order Information</h3>
      <div class="tw-flex tw-flex-col tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-grid-cols-1 max-md:tw-gap-2">
        <div>
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Order Number</label>
          <input type="text" id="orderNumber" name="orderNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->orderNumber }}" readonly>
        </div>
      </div>

      <!-- Custom Divider -->
      <div class="tw-flex tw-items-center tw-justify-center tw-mt-4">
        <div class="tw-mx-auto tw-my-6 tw-w-[30%] tw-h-[2px] tw-bg-slate-100"></div>
        <span class="tw-text-sm tw-font-bold tw-text-gray-200 | max-md:tw-text-xs">DS GLOBAL HOLDING INC.</span>
        <div class="tw-mx-auto tw-my-6 tw-w-[30%] tw-h-[2px] tw-bg-slate-100"></div>
      </div>

      <h3 class="tw-text-md tw-font-bold tw-my-4 tw-text-gray-400 | max-md:tw-text-sm">Payment</h3>
      <div class="tw-flex tw-items-center tw-justify-center tw-relative tw-mb-4">
        <img src="{{ asset('storage/' . $order->payment->first()->paymentUrl) }}" alt="Payment Image" class="tw-max-w-[500px]  tw-max-h-[500px] tw-rounded-md tw-shadow-md tw-object-fill">
        <div style="font-size: 2rem; left: 50%; top: 50%; transform: translate(-50%, -50%);" class="tw-absolute tw-z-10 tw-text-gray-700 tw-opacity-10 tw-font-bold">
          {{ $order->payment->first()->paymentNumber ?? 'N/A' }}
        </div>
      </div>
      <div class="tw-grid tw-grid-cols-2 tw-gap-2 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-grid-cols-1 max-md:tw-gap-2">
        <div>
          <label for="paymentNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Payment Number</label>
          <input type="text" id="paymentNumber" name="paymentNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->payment->first()->paymentNumber }}" readonly>
        </div>
        <div>
          <label for="paymentStatus" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Payment Status</label>
          <input type="text" id="paymentStatus" name="paymentStatus" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ ucfirst($order->payment->first()->approval_status) }}" readonly>
        </div>
        <div>
          <label for="paymentCreate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Payment Create</label>
          <input type="text" id="paymentCreate" name="paymentCreate" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $order->payment->first()->created_at->format('Y-m-d') }}" readonly>
        </div>
      </div>
    </div>
    <div class="tw-flex tw-justify-end tw-mb-6">
      <form class="tw-mr-2" action="{{ route('staff.payment.reject', $order->payment->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="tw-text-white tw-bg-red-600 tw-text-sm tw-font-bold tw-py-2 tw-px-4 tw-rounded | max-md:tw-text-xs">Reject</button>
      </form>
      <form action="{{ route('staff.payment.approve', $order->payment->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="tw-text-white tw-bg-gray-700 tw-text-sm tw-font-bold tw-py-2 tw-px-4 tw-rounded | max-md:tw-text-xs">Mark as Reviewed</button>
      </form>
    </div>
    <hr>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2 | max-md:tw-text-sm">Order Table Instructions</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2 | max-md:tw-text-[12px]">
        <p class="tw-mb-1">In the table above, you can view and manage your order requests created by you by clicking on the "Order No." column of the table. When you click on the "Order No.", you will be redirected to the order details page. </p>
        <p class="tw-mt-2">To add a new order request, click the "Add New" button.</p>
      </div>
    </div>
  </div>

</x-layout.mainTemplate>