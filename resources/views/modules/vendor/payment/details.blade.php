<x-layout.portal.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('vendorPortal.order.payment') }}" :active="false" :isLast="false">
        Payment
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Review Payment (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $payment->paymentNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <div>
      <div class="tw-flex tw-items-center tw-justify-center tw-mb-4">
        @if (Str::endsWith(strtolower($payment->paymentUrl), ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.webp']))
        <img src="{{ asset('storage/' . $payment->paymentUrl) }}"
          class="tw-w-full tw-h-auto tw-rounded-md tw-shadow-md tw-max-w-[800px]"
          alt="Image"> <!-- Use a generic alt text or dynamic based on context -->
        @else
        <p class="tw-text-sm tw-text-gray-500">No preview available. Click to <a href="{{ asset('storage/' . $payment->paymentUrl) }}" target="_blank" class="tw-text-blue-500 hover:tw-text-blue-700 hover:tw-underline">Download</a></p>
        @endif
      </div>
    </div>
    <div class="tw-overflow-x-auto tw-mb-6">
      <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Order Information</h3>
      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs">
        <div>
          <label for="orderNumber" class="tw-font-semibold">Order Number:</label>
          <input type="text" id="orderNumber" name="orderNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ strtoupper(Str::random(20)) }}" readonly>
        </div>
      </div>
      <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Payment</h3>
      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-grid-cols-1 max-md:tw-gap-2">
        <div>
          <label for="name" class="tw-font-semibold">Name:</label>
          <input type="text" id="name" name="name" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER NAME" value="{{ $payment->user->firstName }} {{ $payment->user->lastName }}" readonly>
        </div>
        <div>
          <label for="paymentNumber" class="tw-font-semibold">Payment Number:</label>
          <input type="text" id="paymentNumber" name="paymentNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER PAYMENT NUMBER" value="{{ $payment->paymentNumber }}" readonly>
        </div>
        <div>
          <label for="paymentStatus" class="tw-font-semibold">Payment Status:</label>
          <input type="text" id="paymentStatus" name="paymentStatus" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER PAYMENT STATUS" value="{{ ucfirst($payment->approval_status) }}" readonly>
        </div>
        <div>
          <label for="documentCreatedDate" class="tw-font-semibold">Document Created Date:</label>
          <input type="text" id="documentCreatedDate" name="documentCreatedDate" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER DOCUMENT CREATED DATE" value="{{ $payment->created_at->setTimezone('Asia/Manila')->format('M d, Y') }}" readonly>
        </div>
        <div>
          <label for="documentCreatedTime" class="tw-font-semibold">Document Created Time:</label>
          <input type="text" id="documentCreatedTime" name="documentCreatedTime" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER DOCUMENT CREATED TIME" value="{{ $payment->created_at->setTimezone('Asia/Manila')->format('h:i A') }}" readonly>
        </div>
      </div>

      <!-- Approved By -->
      <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Approved By</h3>
      <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-gap-2">
        <div>
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Name</label>
          <input type="text" id="approvedByName" name="approvedByName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="APPROVED BY NAME" value="{{ optional(App\Models\User::find($payment->approved_by))->firstName }} {{ optional(App\Models\User::find($payment->approved_by))->lastName }}" readonly>
        </div>
        <div>
          <label for="product" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Approved At</label>
          <input type="text" id="product" name="product" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $payment->updated_at->format('Y-m-d') }}" readonly>
        </div>
        <div>
          <label for="deliveryLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Role</label>
          <input type="text" id="deliveryLocation" name="deliveryLocation" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ optional(App\Models\User::find($payment->approved_by))->roles->pluck('name')->first() }}" readonly>
        </div>
       
      </div>
      <div>
        <div class="tw-flex tw-items-center tw-justify-start tw-my-6">
          <a href="{{ route('vendorPortal.order.payment') }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-p-3 ">
            <i class="fa-solid fa-arrow-left tw-mr-2 | max-md:tw-text-xs"></i>
            Back
          </a>
        </div>
      </div>
    </div>
    <hr>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2 | max-md:tw-text-sm">Review After Submission</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2 | max-md:tw-text-[11px]">
        <p class="tw-mb-1">Once the order request is submitted, you will receive an email notification. Please check your email for further instructions. The Staff will review your order request and provide you with a response within 24 hours or as soon as possible.</p>
      </div>
    </div>
  </div>

</x-layout.portal.mainTemplate>