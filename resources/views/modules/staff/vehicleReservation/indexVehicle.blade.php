<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-md:tw-justify-self-end" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-md:tw-hidden">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="false">
        Vehicle Reservation
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        Reservation for Vehicle
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-overflow-x-auto ">
      <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4 | max-sm:tw-text-sm ">

        <thead class="tw-bg-gray-200 tw-text-gray-700 ">
          <tr>
            <th class="tw-px-4 tw-py-2">Reservation For</th>
            <th class="tw-px-4 tw-py-2">Reservation Number </th>
            <th class="tw-px-4 tw-py-2">Approved Date</th>
          </tr>
        </thead>

        <tbody id="reportRecords" class="tw-bg-white">
          @foreach($vehicleReservations as $vehicleReservation)
          <tr>
            <td class="tw-px-4 tw-py-2">{{ $vehicleReservation->user->firstName }} {{ $vehicleReservation->user->lastName }}</td>
            <td class="tw-px-4 tw-py-2">
              @if($vehicleReservation->approval_status === 'reviewed' || $vehicleReservation->approval_status === 'approved')
              {{ $vehicleReservation->reservationNumber }}
              @else
              <a class="tw-text-blue-600 hover:tw-underline" href="{{ route('staff.vehicleReservation.detailsVehicle', $vehicleReservation->id) }}">
                {{ $vehicleReservation->reservationNumber }}
              </a>
              @endif
            </td>
            <td class="tw-px-4 tw-py-2">{{ $vehicleReservation->updated_at->format('n-j-Y') }}</td>
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