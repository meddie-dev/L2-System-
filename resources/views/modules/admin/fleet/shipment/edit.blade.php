<x-layout.portal.mainTemplate>
  <nav class="tw-flex | max-sm:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('admin.fleet.index') }}" :active="false" :isLast="false">
        Manage Shipments
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Review Shipment (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $shipment->trackingNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div>
    <div class="tw-px-4">
      <p class="tw-text-sm tw-text-gray-500"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>
      <form action="{{ route('admin.fleet.update', $shipment->id) }}" enctype="multipart/form-data" method="POST" class="tw-mt-6">
        @csrf
        @method('PATCH')
        <div class="tw-grid tw-grid-cols-2 tw-gap-3 tw-text-sm | max-md:tw-grid-cols-1 max-md:tw-gap-2 ">
          <!-- Tracking Number -->
          <div class="tw-mb-4">
            <label for="trackingNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Tracking Number<span class="tw-text-red-500">*</span></label>
            <input type="text" id="trackingNumber" name="trackingNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-text-gray-400 tw-opacity-50 tw-cursor-not-allowed tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('trackingNumber') is-invalid @enderror" placeholder="Enter tracking number" value="{{ $shipment->trackingNumber }}" readonly>
            @error('trackingNumber')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Shipment Status -->
          <div class="tw-mb-4">
            <label for="shipmentStatus" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Shipment Status<span class="tw-text-red-500">*</span></label>
            <input type="text" id="shipmentStatus" name="shipmentStatus" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-text-gray-400 tw-opacity-50 tw-cursor-not-allowed tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('shipmentStatus') is-invalid @enderror" placeholder="Enter shipment status" value="{{ $shipment->shipmentStatus }}" readonly>
            @error('shipmentStatus')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Departure Time -->
          <div class="tw-mb-4"><label for="departureTime" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Departure Time<span class="tw-text-red-500">*</span></label>
            <input type="datetime-local" id="departureTime" name="departureTime" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('departureTime') is-invalid @enderror" placeholder="Enter departure time" value="{{ $shipment->departureTime }}" required>
            @error('departureTime')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Arrival Time -->
          <div class="tw-mb-4">
            <label for="arrivalTime" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Arrival Time<span class="tw-text-red-500">*</span></label>
            <input type="datetime-local" id="arrivalTime" name="arrivalTime" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('arrivalTime') is-invalid @enderror" placeholder="Enter arrival time" value="{{ $shipment->arrivalTime }}" required>
            @error('arrivalTime')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Current Location -->
          <div class="tw-mb-4">
            <label for="currentLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Current Location<span class="tw-text-red-500">*</span></label>
            <input type="text" id="currentLocation" name="currentLocation" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('currentLocation') is-invalid @enderror" placeholder="Enter current location" value="{{ $shipment->currentLocation }}" required>
            @error('currentLocation')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Estimated Delivery Time -->
          <div class="tw-mb-4">
            <label for="estimatedDeliveryTime" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Estimated Delivery Time<span class="tw-text-red-500">*</span></label>
            <input type="datetime-local" id="estimatedDeliveryTime" name="estimatedDeliveryTime" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('estimatedDeliveryTime') is-invalid @enderror" placeholder="Enter estimated delivery time" value="{{ $shipment->estimatedDeliveryTime }}" required>
            @error('estimatedDeliveryTime')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

        </div>
        <!-- Submit Registration Button -->
        <div class="tw-flex tw-justify-end">
          <button type="submit" class=" tw-bg-indigo-600 tw-text-white tw-px-6 tw-py-2 tw-mb-2 tw-rounded-md tw-shadow-md hover:tw-bg-indigo-700">Update</button>
        </div>
      </form>
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