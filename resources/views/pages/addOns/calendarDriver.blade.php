<x-layout.mainTemplate>
  <div class="container-fluid tw-my-5 | tw-px-4 max-sm:tw-px-0 ">
    <nav class="tw-flex | max-sm:justify-center" aria-label="Breadcrumb">
      <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
        <x-partials.breadcrumb href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : (Auth::user()->hasRole('Staff') ? 'staff.dashboard' : 'driver.dashboard'))) }}" :active="false" :isLast="false">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
          Dashboard
        </x-partials.breadcrumb>

        <x-partials.breadcrumb :active="true" :isLast="true">
          Calendar and Schedule
        </x-partials.breadcrumb>
      </ol>
    </nav>

    <div class="tw-max-w-9xl tw-h-full tw-mx-auto tw-mt-3 tw-mb-6 tw-space-y-6">
      <div class="tw-w-full tw-h-full" id="calendar"></div>
    </div>

    <div class="modal fade tw-hidden" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="tw-text-md tw-font-bold" id="reservationModalTitle">Selected Date: <span id="reservation-dateTitle"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="reservationModalDescription">
            <dl class="row">
              <h5 class="tw-text-md tw-font-bold tw-mb-2" id="reservationModalTitle">Reservation Details</h5>

              <dt class="col-sm-4 tw-font-semibold tw-text-sm">Reservation No:</dt>
              <dd class="col-sm-8 tw-text-sm" id="reservation-number"></dd>

              <dt class="col-sm-4 tw-font-semibold tw-text-sm">Vehicle Type:</dt>
              <dd class="col-sm-8 tw-text-sm" id="vehicle-type"></dd>

              <dt class="col-sm-4 tw-font-semibold tw-text-sm">Reservation Date:</dt>
              <dd class="col-sm-8 tw-text-sm" id="reservation-date"></dd>

              <dt class="col-sm-4 tw-font-semibold tw-text-sm">Reservation Time:</dt>
              <dd class="col-sm-8 tw-text-sm" id="reservation-time"></dd>

              <dt class="col-sm-4 tw-font-semibold tw-text-sm">Pickup Location:</dt>
              <dd class="col-sm-8 tw-text-sm" id="pickup-location"></dd>

              <dt class="col-sm-4 tw-font-semibold tw-text-sm">Dropoff Location:</dt>
              <dd class="col-sm-8 tw-text-sm" id="dropoff-location"></dd>

              <dt class="col-sm-4 tw-font-semibold tw-text-sm">Approval Status:</dt>
              <dd class="col-sm-8 tw-text-sm" id="approval-status"></dd>
            </dl>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script>
    const existingReservations = <?php echo json_encode($vehicleReservations); ?>;
  </script>
</x-layout.mainTemplate>