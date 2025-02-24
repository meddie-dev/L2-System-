<x-layout.portal.mainTemplate>
  <nav class="tw-flex tw-justify-between |  max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('vendorPortal.order.document') }}" :active="false" :isLast="false">
        Vehicle Reservation
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Review Reservation (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $vehicleReservation->reservationNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-overflow-x-auto tw-mb-6">
      <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Order Information</h3>
      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs ">
        <div>
          <p class=" tw-font-semibold" >Order Number:</p>
          <p class="tw-text-gray-700">{{ $vehicleReservation->order->orderNumber ?? 'N/A' }}</p>
        </div>
      </div>
      <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Reservation Information</h3>
      <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-grid-cols-1 max-md:tw-gap-2">
        <div>
          <p class="tw-font-semibold">Reservation Number:</p>
          <p class="tw-text-gray-700">{{ $vehicleReservation->reservationNumber }}</p>
        </div>
        <div>
          <p class="tw-font-semibold">Reservation Date:</p>
          <p class="tw-text-gray-700">{{ $vehicleReservation->reservationDate }}</p>
        </div>
        <div>
          <p class="tw-font-semibold">Reservation Time:</p>
          <p class="tw-text-gray-700">{{ $vehicleReservation->reservationTime }}</p>
        </div>
        <div>
          <p class="tw-font-semibold">Vehicle Type:</p>
          <p class="tw-text-gray-700">{{ ucfirst( $vehicleReservation->vehicle_type) }}</p>
        </div>
        <div>
          <p class="tw-font-semibold">Pick Up Location:</p>
          <p class="tw-text-gray-700">{{ $vehicleReservation->pickUpLocation }}</p>
        </div>
        <div>
          <p class="tw-font-semibold">Drop Off Location:</p>
          <p class="tw-text-gray-700">{{ $vehicleReservation->dropOffLocation }}</p>
        </div>
        <div>
          <p class="tw-font-semibold">Status:</p>
          <p class="tw-text-gray-700">{{ ucfirst($vehicleReservation->approval_status) }}</p>
        </div>
      </div>
      <div>
        <div class="tw-flex tw-items-center tw-justify-start tw-mt-6">
          <a href="{{ route('vendorPortal.vehicleReservation') }}" class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-bg-white tw-text-gray-700 tw-font-semibold tw-text-sm tw-cursor-pointer | max-md:tw-text-xs">
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