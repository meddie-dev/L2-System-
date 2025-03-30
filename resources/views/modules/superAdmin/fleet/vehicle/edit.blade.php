<x-layout.portal.mainTemplate>
  <nav class="tw-flex | max-sm:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb  :active="true" :isLast="false">
        Vehicle Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Vehicle (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $vehicle->plateNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div>
    <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
      <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Vehicle Details</h2>
      <p class="tw-text-xs | max-md:tw-text-xs">Edit and update your vehicle's information.</p>
    </div>
    <div class="tw-px-4">
      <p class="tw-text-sm tw-text-gray-500"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>
      <form action="{{ route('superadmin.fleet.update', $vehicle->id) }}" enctype="multipart/form-data" method="POST" class="tw-mt-6">
        @csrf
        @method('PATCH')
        <div class="tw-grid tw-grid-cols-2 tw-gap-3 tw-text-sm | max-md:tw-grid-cols-1 max-md:tw-gap-2 ">
          <!-- Plate Number -->
          <div class="tw-mb-4">
            <label for="plateNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Plate Number<span class="tw-text-red-500">*</span></label>
            <input type="text" id="plateNumber" name="plateNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('plateNumber') is-invalid @enderror" placeholder="Enter plate number" value="{{ $vehicle->plateNumber }}" required>
            @error('plateNumber')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Vehicle Type -->
          <div class="tw-mb-4">
            <label for="vehicleType" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Type<span class="tw-text-red-500">*</span></label>
            <div style="color: gray">
              <select id="vehicleType" name="vehicleType" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleType') is-invalid @enderror" value="{{ $vehicle->vehicleType }}" required>
                <option value="" disabled>Select vehicle type</option>
                <option value="light">Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)</option>
                <option value="medium">Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)</option>
                <option value="heavy">Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)</option>
              </select>
            </div>
            @error('vehicleType')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Vehicle Model -->
          <div class="tw-mb-4">
            <label for="vehicleModel" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Model<span class="tw-text-red-500">*</span></label>
            <input type="text" id="vehicleModel" name="vehicleModel" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleModel') is-invalid @enderror" placeholder="Enter vehicle model" value="{{ $vehicle->vehicleModel }}" required>
            @error('vehicleModel')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Vehicle Make -->
          <div class="tw-mb-4">
            <label for="vehicleMake" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Make<span class="tw-text-red-500">*</span></label>
            <input type="text" id="vehicleMake" name="vehicleMake" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleMake') is-invalid @enderror" placeholder="Enter vehicle make" value="{{ $vehicle->vehicleMake }}" required>
            @error('vehicleMake')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Vehicle Color -->
          <div class="tw-mb-4">
            <label for="vehicleColor" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Color<span class="tw-text-red-500">*</span></label>
            <input type="text" id="vehicleColor" name="vehicleColor" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleColor') is-invalid @enderror" placeholder="Enter vehicle color" value="{{ $vehicle->vehicleColor }}" required>
            @error('vehicleColor')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Vehicle Year -->
          <div class="tw-mb-4">
            <label for="vehicleYear" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Year<span class="tw-text-red-500">*</span></label>
            <input type="number" id="vehicleYear" name="vehicleYear" class="@error('vehicleYear') is-invalid @enderror tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs " placeholder="Enter vehicle year" required min="1900" max="2099" step="1" value="{{ $vehicle->vehicleYear }}">
            @error('vehicleYear')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Vehicle Fuel Type -->
          <div class="tw-mb-4">
            <label for="vehicleFuelType" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Fuel Type<span class="tw-text-red-500">*</span></label>
            <select id="vehicleFuelType" name="vehicleFuelType" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-text-gray-500 | max-md:tw-text-xs @error('vehicleFuelType') is-invalid @enderror" required value="{{ $vehicle->vehicleFuelType }}">
              <option value="" disabled>Select vehicle fuel type</option>
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
            <input type="number" id="fuel_efficiency" name="fuel_efficiency" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('fuel_efficiency') is-invalid @enderror" placeholder="Enter fuel efficiency" min="0" required step="0.1" value="{{ $vehicle->fuel_efficiency }}">
            @error('fuel_efficiency')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Vehicle Capacity -->
          <div class="tw-mb-4">
            <label for="vehicleCapacity" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Capacity (kg)<span class="tw-text-red-500">*</span></label>
            <input type="number" id="vehicleCapacity" name="vehicleCapacity" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleCapacity') is-invalid @enderror" placeholder="Enter vehicle capacity" required min="1" max="9999" step="1" value="{{ $vehicle->vehicleCapacity }}">
            @error('vehicleCapacity')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Vehicle Cost -->
          <div class="tw-mb-4">
            <label for="vehicleCost" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Cost (Brand New)<span class="tw-text-red-500">*</span></label>
            <input type="number" id="vehicleCost" name="vehicleCost" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleCost') is-invalid @enderror" placeholder="Enter vehicle cost" required min="0" step="0.01" value="{{ $vehicle->vehicleCost }}">
            @error('vehicleCost')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Vehicle Lifespan -->
          <div class="tw-mb-4">
            <label for="vehicleLifespan" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Lifespan (years)<span class="tw-text-red-500">*</span></label>
            <input type="number" id="vehicleLifespan" name="vehicleLifespan" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicleLifespan') is-invalid @enderror" placeholder="Enter vehicle lifespan" required min="1" max="9999" step="1" value="{{ $vehicle->vehicleLifespan }}">
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
        <div class="tw-flex tw-justify-end">
          <button type="submit" class=" tw-bg-indigo-600  tw-text-white tw-px-6 tw-py-2 tw-mb-2 tw-rounded-md tw-shadow-md hover:tw-bg-indigo-700">Update</button>
        </div>
      </form>

    </div>

    <!-- Maintenance Tracking -->
    <div class="card-body">
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Maintenance Tracking</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">Ensure that all vehicle details are correct and current for effective maintenance tracking.</p>
      </div>
      <div class="tw-px-4">
        <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4">
          <thead class="tw-bg-gray-200 tw-text-gray-700">
            <tr>
              <th class="tw-px-4 tw-py-2">Maintenance Number</th>
              <th class="tw-px-4 tw-py-2">Task</th>
              <th class="tw-px-4 tw-py-2">Amount</th>
              <th class="tw-px-4 tw-py-2">Schedule</th>
              <th class="tw-px-4 tw-py-2">Completed Date</th>
              <th class="tw-px-4 tw-py-2">Condition Status</th>
            </tr>
          </thead>
          <tbody id="orderRecords" class="tw-bg-white">
            @foreach($maintenances as $maintenance)
            <tr>
              <td class="tw-px-4 tw-py-2">{{ $maintenance->maintenanceNumber }}</td>
              <td class="tw-px-4 tw-py-2">{{ $maintenance->task }}</td>
              <td class="tw-px-4 tw-py-2">{{ $maintenance->amount }}</td>
              <td class="tw-px-4 tw-py-2">{{ $maintenance->scheduled_date }}</td>
              <td class="tw-px-4 tw-py-2">{{ $maintenance->completed_date }}</td>
              <td class="tw-px-4 tw-py-2">
                @switch($maintenance->conditionStatus)
                @case('good')
                <span class="tw-text-green-500">{{ ucfirst($maintenance->conditionStatus) }}</span>
                @break

                @case('fair')
                <span class="tw-text-yellow-500">{{ ucfirst($maintenance->conditionStatus) }}</span>
                @break

                @case('poor')
                <span class="tw-text-red-500">{{ ucfirst($maintenance->conditionStatus) }}</span>
                @break

                @default
                <span class="tw-text-gray-500">{{ ucfirst($maintenance->conditionStatus) }}</span>
                @endswitch
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Fuel & Mileage Reports -->
    <div class="card-body">
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Fuel & Mileage Reports</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">Ensure that all fuel and mileage data are accurate and up-to-date for efficient tracking and reporting.</p>
      </div>
      <div class="tw-px-4">
        <table class=" datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4">
          <thead class="tw-bg-gray-200 tw-text-gray-700">
            <tr>
              <th class="tw-px-4 tw-py-2">Fuel Number</th>
              <th class="tw-px-4 tw-py-2">Fuel Type</th>
              <th class="tw-px-4 tw-py-2">Estimated Fuel Consumption</th>
              <th class="tw-px-4 tw-py-2">Estimated Cost</th>
              <th class="tw-px-4 tw-py-2">Fuel Date</th>
              <th class="tw-px-4 tw-py-2">Fuel Status</th>
            </tr>
          </thead>
          <tbody id="orderRecords" class="tw-bg-white">
            @foreach ($fuels as $fuel)
            <tr class="tw-bg-gray-100">
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{ $fuel->fuelNumber }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{ ucfirst($fuel->fuelType) }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{ number_format($fuel->estimatedFuelConsumption, 2) . ' L' }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">PHP {{ number_format($fuel->estimatedCost, 2) }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{ \Carbon\Carbon::parse($fuel->fuelDate)->format('Y-m-d') }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">
                @switch($fuel->fuelStatus)
                @case('scheduled')
                <span class="tw-text-yellow-500">{{ ucfirst($fuel->fuelStatus) }}</span>
                @break
                @case('completed')
                <span class="tw-text-green-500">{{ ucfirst($fuel->fuelStatus) }}</span>
                @break
                @endswitch
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
    <div class="tw-flex tw-justify-start">
      <div class="tw-flex tw-items-center tw-justify-start tw-my-6">
        <a href="{{ route('superadmin.fleet.vehicle.index') }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-p-3 ">
          <i class="fa-solid fa-arrow-left tw-mr-2 | max-md:tw-text-xs"></i>
          Back
        </a>
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