<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between " aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | max-md:tw-hidden md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white " href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon "><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('admin.vehicleReservation.manage') }}" :active="false" :isLast="false">
        Vehicle Reservation
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Review Reservation (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $vehicleReservation->reservationNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
    <x-partials.breadcrumb :active="true" :isLast="true">
      <div class="tw-flex tw-items-center tw-justify-center tw-text-xs | sm:tw-hidden">
        Review Reservation (<span class="tw-font-semibold tw-text-[10px] tw-opacity-20 tw-mt-1 ">{{ $vehicleReservation->reservationNumber }}</span>)
      </div>
    </x-partials.breadcrumb>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-overflow-x-auto tw-mb-6">
      <div class="tw-flex tw-w-full  tw-gap-6">
        <!-- Reservation Date -->
        <div class="tw-mb-4 tw-w-full | max-md:tw-hidden" id="showCalendar"></div>
      </div>
      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-text-sm | max-md:tw-grid-cols-1 max-md:tw-gap-2 ">
        <!-- Reservation Number -->
        <div class="tw-mb-4">
          <label for="reservationNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Reservation Number</label>
          <input type="text" id="reservationNumber" name="reservationNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ $vehicleReservation->reservationNumber }}" readonly>
        </div>

        <!-- Reservation Request Date -->
        <div class="tw-mb-4">
          <label for="reservationDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Reservation Request Date</label>
          <div style="color: gray">
            <input type="date" id="reservationDate" name="reservationDate" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="Enter delivery request date" value="{{ $vehicleReservation->reservationDate }}" readonly>
          </div>
        </div>

        <!-- Reservation Request Time -->
        <div class="tw-mb-4">
          <label for="reservationTime" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Reservation Request Time</label>
          <div style="color: gray">
            <input type="time" id="reservationTime" name="reservationTime" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="Enter delivery request time" value="{{ $vehicleReservation->reservationTime }}" readonly>
          </div>
        </div>

        <!-- Vehicle Type -->
        <div class="tw-mb-4">
          <label for="vehicle_type" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Type</label>
          <div style="color: gray">
            <input type="text" id="vehicle_type" name="vehicle_type" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $vehicleReservation->vehicle_type === 'light' ? 'Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)' : ($vehicleReservation->vehicle_type === 'medium' ? 'Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)' : ($vehicleReservation->vehicle_type === 'heavy' ? 'Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)' : 'N/A')) }}" readonly>
          </div>
        </div>

        <!-- Pick Up Location -->
        <div class="tw-mb-4">
          <label for="pickUpLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Pick Up Location</label>
          <div style="color: gray">
            <input type="text" id="pickUpLocation" name="pickUpLocation" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="Enter pick up location" value="{{ $vehicleReservation->pickUpLocation }}" readonly>
          </div>
        </div>

        <!-- Drop Off Location -->
        <div class="tw-mb-4">
          <label for="dropOffLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Drop Off Location</label>
          <div style="color: gray">
            <input type="text" id="dropOffLocation" name="dropOffLocation" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="Enter drop off location" value="{{ $vehicleReservation->dropOffLocation }}" readonly>
          </div>
        </div>
      </div>
    </div>
    <div class="tw-flex tw-justify-end tw-mb-6">
      <form class="tw-mr-2" action="{{ route('admin.vehicleReservation.reject', $vehicleReservation->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="tw-text-white tw-bg-red-600 tw-text-sm tw-font-bold tw-py-2 tw-px-4 tw-rounded | max-md:tw-text-xs">Reject</button>
      </form>
      <form action="{{ route('admin.vehicleReservation.approve', $vehicleReservation->id)}}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="tw-text-white tw-bg-gray-700 tw-text-sm tw-font-bold tw-py-2 tw-px-4 tw-rounded | max-md:tw-text-xs">Approve Reservation</button>
      </form>
    </div>
    <hr>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2 | max-md:tw-text-sm">Order Table Instructions</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2 | max-md:tw-text-[12px]">
        <p class="tw-mb-1">In the table above, you can view and manage your order requests created by you by clicking on the "Order No." column of the table. When you click on the "Order No.", you will be redirected to the order details page. </p>
        <p class="tw-mt-2">To add a new order request, click the "Add New" button.</p>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('showCalendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
          left: 'prev',
          center: 'title',
          right: 'next'
        },
        initialDate: '{{ $vehicleReservation->reservationDate }}',
        initialTime: '{{ $vehicleReservation->reservationTime }}',
        initialView: 'dayGridWeek',
        aspectRatio: 4,
        views: {
          timeGrid: {
            dayMaxEventRows: 5
          }
        },
        dayMaxEventRows: true, // for all non-TimeGrid views
        events: [{
          title: '{{ $vehicleReservation->reservationNumber }}', // Display reservation number as title
          start: '{{ $vehicleReservation->reservationDate }}T{{ $vehicleReservation->reservationTime }}',
          end: '{{ $vehicleReservation->reservationDate }}T{{ $vehicleReservation->reservationTime }}',
          color: '{{ $vehicleReservation->approval_status === '
          approved ' ? '
          green ' : '
          yellow ' }}',

        }],
        businessHours: [{
            daysOfWeek: [1, 2, 3], // Monday, Tuesday, Wednesday
            startTime: '08:00', // 8am
            endTime: '18:00', // 6pm
          },
          {
            daysOfWeek: [4, 5], // Thursday, Friday
            startTime: '08:00', // 8am
            endTime: '18:00', // 6pm
          }
        ],
      });
      calendar.render();
    });
  </script>
</x-layout.mainTemplate>