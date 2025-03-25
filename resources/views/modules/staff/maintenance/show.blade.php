<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-md:tw-justify-self-end" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-md:tw-hidden">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="false">
        Vehicle Maintenance
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        Vehicle PLate Number: (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $vehicle->plateNumber }}</span>)
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-overflow-x-auto ">
      <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4 | max-sm:tw-text-sm ">

        <thead class="tw-bg-gray-200 tw-text-gray-700 ">
          <tr>
            <th class="tw-px-4 tw-py-2">Maintenance Number</th>
            <th class="tw-px-4 tw-py-2">Task</th>
            <th class="tw-px-4 tw-py-2">Amount</th>
            <th class="tw-px-4 tw-py-2">Schedule</th>
            <th class="tw-px-4 tw-py-2">Completed Date</th>
            <th class="tw-px-4 tw-py-2">Condition Status</th>
          </tr>
        </thead>

        <tbody id="reportRecords" class="tw-bg-white">
          @foreach($maintenance as $maintenances)
          <tr>
            <td class="tw-px-4 tw-py-2">
              <a class="tw-text-blue-600 hover:tw-underline" href="">
                {{ $maintenances->maintenanceNumber }}
              </a>
            </td>
            <td class="tw-px-4 tw-py-2">
                {{ $maintenances->task }}
            </td>
            <td class="tw-px-4 tw-py-2">PHP {{ number_format($maintenances->amount, 2) }}</td>
            <td class="tw-px-4 tw-py-2">{{ \Carbon\Carbon::parse($maintenances->scheduled_date)->format('m-d-Y') }}</td>
            <td class="tw-px-4 tw-py-2">{{ $maintenances->completed_date ? \Carbon\Carbon::parse($maintenances->completed_date)->format('m-d-Y') : 'N/A' }}</td>
            <td class="tw-px-4 tw-py-2">
              @switch($maintenances->conditionStatus)
                @case('good')
                <span class="tw-text-green-500">{{ ucfirst($maintenances->conditionStatus) }}</span>
                @break

                @case('fair')
                <span class="tw-text-yellow-500">{{ ucfirst($maintenances->conditionStatus) }}</span>
                @break

                @case('poor')
                <span class="tw-text-red-500">{{ ucfirst($maintenances->conditionStatus) }}</span>
                @break

                @default
                <span class="tw-text-gray-500">{{ ucfirst($maintenances->conditionStatus) }}</span>
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
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2 | max-md:tw-text-[11px]">
        <p class="tw-mb-1">In the table above, you can view and manage your order requests created by you by clicking on the "Order No." column of the table. When you click on the "Order No.", you will be redirected to the order details page. </p>
        <p class="tw-mt-2">To add a new order request, click the "Add New" button.</p>
      </div>
    </div>
  </div>

</x-layout.mainTemplate>