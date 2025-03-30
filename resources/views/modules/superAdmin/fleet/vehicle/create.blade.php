<x-layout.mainTemplate>
  <nav class="tw-flex | max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb  :active="true" :isLast="false">
        Fleet Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        Add New Vehicle
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="tw-px-4">
    <p class="tw-text-sm tw-text-gray-500 | max-md:tw-text-xs"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>
    <form id="orderForm" action="{{ route('superadmin.fleet.store') }}" enctype="multipart/form-data" method="POST" class="tw-mt-6">
      @csrf
      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-text-sm | max-md:tw-grid-cols-1 max-md:tw-gap-2 ">
        <!-- Plate Number -->
        <div class="tw-mb-4">
          <label for="plateNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Plate Number<span class="tw-text-red-500">*</span></label>
          <input type="text" id="plateNumber" name="plateNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('plateNumber') is-invalid @enderror" placeholder="Enter plate number" required>
          @error('plateNumber')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Vehicle Type -->
        <div class="tw-mb-4">
          <label for="vehicleType" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Type<span class="tw-text-red-500">*</span></label>
          <select id="vehicleType" name="vehicleType" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleType') is-invalid @enderror" required>
            <option value="" disabled selected>Select vehicle type</option>
            <option value="light">Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)</option>
            <option value="medium">Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)</option>
            <option value="heavy">Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)</option>
          </select>
          @error('vehicleType')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Vehicle Model -->
        <div class="tw-mb-4">
          <label for="vehicleModel" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Model<span class="tw-text-red-500">*</span></label>
          <input type="text" id="vehicleModel" name="vehicleModel" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleModel') is-invalid @enderror" placeholder="Enter vehicle model" required>
          @error('vehicleModel')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Vehicle Make -->
        <div class="tw-mb-4">
          <label for="vehicleMake" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Make<span class="tw-text-red-500">*</span></label>
          <input type="text" id="vehicleMake" name="vehicleMake" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleMake') is-invalid @enderror" placeholder="Enter vehicle make" required>
          @error('vehicleMake')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Vehicle Color -->
        <div class="tw-mb-4">
          <label for="vehicleColor" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Color<span class="tw-text-red-500">*</span></label>
          <input type="text" id="vehicleColor" name="vehicleColor" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleColor') is-invalid @enderror" placeholder="Enter vehicle color" required>
          @error('vehicleColor')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Vehicle Year -->
        <div class="tw-mb-4">
          <label for="vehicleYear" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Year<span class="tw-text-red-500">*</span></label>
          <input type="number" id="vehicleYear" name="vehicleYear" class="@error('vehicleYear') is-invalid @enderror tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs " placeholder="Enter vehicle year" required min="2015" max="2099" step="1">
          @error('vehicleYear')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Vehicle Fuel Type -->
        <div class="tw-mb-4">
          <label for="vehicleFuelType" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Fuel Type<span class="tw-text-red-500">*</span></label>
          <select id="vehicleFuelType" name="vehicleFuelType" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-text-gray-500 | max-md:tw-text-xs @error('vehicleFuelType') is-invalid @enderror" required>
            <option value="" selected disabled>Select vehicle fuel type</option>
            <option value="diesel">Diesel</option>
            <option value="gasoline">Gasoline</option>
            <option value="electric">Electric</option>
          </select>
          @error('vehicleFuelType')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror

        </div>

        <!-- Vehicle Fuel Efficiency -->
        <div class="tw-mb-4">
          <label for="fuel_efficiency" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Fuel Efficiency (km/l)<span class="tw-text-red-500">*</span></label>
          <input type="number" id="fuel_efficiency" name="fuel_efficiency" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('fuel_efficiency') is-invalid @enderror" placeholder="Enter fuel efficiency" min="0" required step="0.1">
          @error('fuel_efficiency')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Vehicle Capacity -->
        <div class="tw-mb-4">
          <label for="vehicleCapacity" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Capacity (kg)<span class="tw-text-red-500">*</span></label>
          <input type="number" id="vehicleCapacity" name="vehicleCapacity" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleCapacity') is-invalid @enderror" placeholder="Enter vehicle capacity" required min="1" max="9999" step="1">
          @error('vehicleCapacity')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Vehicle Cost -->
        <div class="tw-mb-4">
          <label for="vehicleCost" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Cost (Brand New)<span class="tw-text-red-500">*</span></label>
          <input type="number" id="vehicleCost" name="vehicleCost" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleCost') is-invalid @enderror" placeholder="Enter vehicle cost" required min="0" step="0.01">
          @error('vehicleCost')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Vehicle Lifespan -->
        <div class="tw-mb-4">
          <label for="vehicleLifespan" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Lifespan (years)<span class="tw-text-red-500">*</span></label>
          <input type="number" id="vehicleLifespan" name="vehicleLifespan" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleLifespan') is-invalid @enderror" placeholder="Enter vehicle lifespan" required min="1" max="9999" step="1">
          @error('vehicleLifespan')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Vehicle Image -->
        <div class="tw-mb-4">
          <label for="vehicleImage" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Image<span class="tw-text-red-500">*</span></label>
          <input type="file" id="vehicleImage" name="vehicleImage" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleImage') is-invalid @enderror" required accept="image/jpg, image/jpeg, image/png">
          @error('vehicleImage')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

      </div>
      <!-- Submit Registration Button -->
      <div class="tw-my-6 tw-flex tw-justify-end | max-md:tw-my-2">
        <button type="submit" id="submitBtn" class=" tw-bg-indigo-600 tw-text-white tw-px-6 tw-py-2 tw-mb-2 tw-rounded-md tw-shadow-md hover:tw-bg-indigo-700 | max-md:tw-text-sm">Proceed</button>
      </div>
    </form>
    <hr>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2 | max-md:tw-text-sm">Review After Submission</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2 | max-md:tw-text-[11px]">
        <p class="tw-mb-1">Once the order request is submitted, you will receive an email notification. Please check your email for further instructions. The Staff will review your order request and provide you with a response within 24 hours or as soon as possible.</p>
      </div>
    </div>
  </div>
</x-layout.mainTemplate>