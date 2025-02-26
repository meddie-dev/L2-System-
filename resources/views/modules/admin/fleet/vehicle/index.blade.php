<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="false">
        Fleet Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        Manage Vehicles
      </x-partials.breadcrumb>
    </ol>

    <div class="tw-flex">
      <a href="{{ route('admin.fleet.vehicle.new') }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-1 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-p-3 ">
        <i class="fa-solid fa-plus tw-text-xl | max-md:tw-text-sm"></i>
        <span class="tw-pl-1 tw-text-sm | max-md:tw-text-xs">Add New Vehicle</span>
      </a>
    </div>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-overflow-x-auto ">
      <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4 | max-sm:tw-text-sm">

        <thead class="tw-bg-gray-200 tw-text-gray-700 ">
          <tr>
            <th class="tw-px-4 tw-py-2">ID</th>
            <th class="tw-px-4 tw-py-2">Plate Number</th>
            <th class="tw-px-4 tw-py-2">Type</th>
            <th class="tw-px-4 tw-py-2">Status</th>
          </tr>
        </thead>

        <tbody id="reportRecords" class="tw-bg-white">
          @foreach($vehicles as $vehicle)
          <tr class="hover:tw-bg-gray-100">
            <td class="tw-px-4 tw-py-2"> {{ $vehicle->id }}</td>
            <td class="tw-px-4 tw-py-2"><a href="{{ route('admin.fleet.edit', $vehicle->id) }}">{{ $vehicle->plateNumber }}</a></td>
            <td>{{ ucfirst($vehicle->vehicleType) }}</td>
            <td class="tw-px-4 tw-py-2">
              @switch($vehicle->vehicleStatus)
              @case('available')
              <span class="tw-text-green-500">{{ ucfirst($vehicle->vehicleStatus) }}</span>
              @break
              @case('unavailable')
             <span class="tw-text-red-500">{{ ucfirst($vehicle->vehicleStatus) }}</span>
              @break
              @case('maintenance')
               <span class="tw-text-yellow-500">{{ ucfirst($vehicle->vehicleStatus) }}</span>
              @break
              @endswitch
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <hr>
    </div>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2 | max-md:tw-text-sm">Order Table Instructions</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2 | max-md:tw-text-[12px]">
        <p class="tw-mb-1">In the table above, you can view and manage your order requests created by you by clicking on the "Order No." column of the table. When you click on the "Order No.", you will be redirected to the order details page. </p>
        <p class="tw-mt-2">To add a new order request, click the "Add New" button.</p>
      </div>
    </div>
  </div>

</x-layout.mainTemplate>