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
          <p class=" tw-font-semibold">Order Number:</p>
          <p class="tw-text-gray-700">{{ $payment->order->orderNumber ?? 'N/A' }}</p>
        </div>
      </div>
      <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Payment</h3>
      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-grid-cols-1 max-md:tw-gap-2">
        <div>
          <p class="tw-font-semibold">Name:</p>
          <p class="tw-text-gray-700">{{ $payment->user->firstName }} {{ $payment->user->lastName }}</p>
        </div>
        <div>
          <p class="tw-font-semibold">Payment Number:</p>
          <p class="tw-text-gray-700">{{ $payment->paymentNumber }}</p>
        </div>
        <div>
          <p class="tw-font-semibold">Payment Status:</p>
          <p class="tw-text-gray-700">{{ ucfirst($payment->approval_status) }}</p>
        </div>
        <div>
          <p class="tw-font-semibold">Document Created Date:</p>
          <p class="tw-text-gray-700">{{ $payment->created_at->setTimezone('Asia/Manila')->format('M d, Y') }}</p>
        </div>
        <div>
          <p class="tw-font-semibold">Document Created Time:</p>
          <p class="tw-text-gray-700">{{ $payment->created_at->setTimezone('Asia/Manila')->format('h:i A') }}</p>
        </div>
      </div>
      <div>
        <div class="tw-flex tw-items-center tw-justify-start tw-mt-6">
          <a href="{{ route('vendorPortal.order.document') }}" class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-bg-white tw-text-gray-700 tw-font-semibold tw-text-sm tw-cursor-pointer | max-md:tw-text-xs">
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