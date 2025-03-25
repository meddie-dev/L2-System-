<x-layout.portal.mainTemplate>
  <nav class="tw-flex | max-sm:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('admin.asset.index') }}" :active="false" :isLast="false">
        Asset Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Vehicle (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $assetReport->vehicle->plateNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  
    <!-- Vehicle Details -->
    <div class="card-body">
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Vehicle Details</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">Ensure that all vehicle details are correct and current for effective tracking and reporting.</p>
      </div>
      <div class="tw-px-4">
        <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4">
          <thead class="tw-bg-gray-200 tw-text-gray-700">
            <tr>
              <th class="tw-px-4 tw-py-2">Plate Number</th>
              <th class="tw-px-4 tw-py-2">Year</th>
              <th class="tw-px-4 tw-py-2">Fuel Type</th>
              <th class="tw-px-4 tw-py-2">Capacity</th>
              <th class="tw-px-4 tw-py-2">Cost</th>
              <th class="tw-px-4 tw-py-2">Lifespan</th>
              <th class="tw-px-4 tw-py-2">Status</th>
              <th class="tw-px-4 tw-py-2">Fuel Efficiency</th>
              <th class="tw-px-4 tw-py-2">Created Date</th>
            </tr>
          </thead>
          <tbody id="orderRecords" class="tw-bg-white">
            <tr>
              <td class="tw-px-4 tw-py-2">{{ $assetReport->vehicle->plateNumber }}</td>
              <td class="tw-px-4 tw-py-2">{{ $assetReport->vehicle->vehicleYear }}</td>
              <td class="tw-px-4 tw-py-2">{{ ucfirst($assetReport->vehicle->vehicleFuelType) }}</td>
              <td class="tw-px-4 tw-py-2">{{ ucfirst($assetReport->vehicle->vehicleCapacity) }}</td>
              <td class="tw-px-4 tw-py-2">{{ $assetReport->vehicle->vehicleCost }}</td>
              <td class="tw-px-4 tw-py-2">{{ $assetReport->vehicle->vehicleLifespan }}</td>
              <td class="tw-px-4 tw-py-2">
                <span class="tw-text-{{ ucfirst($assetReport->vehicle->vehicleStatus) === 'Available' ? 'green-500' : (ucfirst($assetReport->vehicle->vehicleStatus) === 'Unavailable' ? 'red-500' : 'yellow-500') }}">
                  {{ ucfirst($assetReport->vehicle->vehicleStatus) }}
                </span>
              </td>
              <td class="tw-px-4 tw-py-2">{{ $assetReport->vehicle->fuel_efficiency }}</td>
              <td class="tw-px-4 tw-py-2">{{ $assetReport->created_at->format('Y-m-d') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Asset Information -->
    <div class="card-body">
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Asset Information</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">Ensure that all asset information are correct and current for efficient tracking and reporting.</p>
      </div>
      <div class="tw-px-4">
        <table class=" datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4">
          <thead class="tw-bg-gray-200 tw-text-gray-700">
            <tr>
              <th class="tw-px-4 tw-py-2">Vehicle ID</th>
              <th class="tw-px-4 tw-py-2">Year</th>
              <th class="tw-px-4 tw-py-2">Annual Depreciation</th>
              <th class="tw-px-4 tw-py-2">Maintenance Cost</th>
              <th class="tw-px-4 tw-py-2">Recommendation</th>
              <th class="tw-px-4 tw-py-2">Created At</th>
            </tr>
          </thead>
          <tbody id="orderRecords" class="tw-bg-white">
            <tr class="tw-bg-gray-100">
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{ $assetReport->vehicle->plateNumber }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{ $assetReport->year }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">PHP {{ number_format($assetReport->annual_depreciation, 2)}}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">PHP {{ number_format($assetReport->maintenance_cost, 2) }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">
                <span class="tw-text-green-500 {{ $assetReport->recommendation !== 'maintain' ? 'tw-text-red-500' : '' }}">
                  {{ ucfirst($assetReport->recommendation) }}
                </span>
              </td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{ \Carbon\Carbon::parse($assetReport->created_at)->format('Y-m-d') }}</td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>

    <div class="tw-flex tw-justify-start">
      <div class="tw-flex tw-items-center tw-justify-start tw-my-6">
        <a href="{{ route('admin.asset.index') }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-p-3 ">
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
  
</x-layout.portal.mainTemplate>