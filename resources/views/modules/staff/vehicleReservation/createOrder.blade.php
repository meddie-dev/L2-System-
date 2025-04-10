<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white " href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon "><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('staff.vehicleReservation.indexOrder') }}" :active="false" :isLast="false">
        Reservation for Order
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Create Reservation for Order No.(<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $order->orderNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="tw-px-4">
    <p class="tw-text-sm tw-text-gray-500 | max-md:tw-text-xs"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>
    <form id="orderForm" action="{{ route('staff.vehicleReservation.storeOrder',  $order->id) }}" enctype="multipart/form-data" method="POST" class="tw-mt-6">
      @csrf
      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-text-sm | max-md:tw-grid-cols-1 max-md:tw-gap-2 ">
        <!-- Reservation Number -->
        <div class="tw-mb-4">
          <label for="reservationNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Reservation Number<span class="tw-text-red-500">*</span></label>
          <input type="text" id="reservationNumber" name="reservationNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('reservationNumber') is-invalid @enderror" placeholder="ENTER ORDER NUMBER" value="{{ strtoupper(Str::random(20)) }}" readonly>
          @error('reservationNumber')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Reservation Request Date -->
        <div class="tw-mb-4">
          <label for="reservationDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Reservation Request Date<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <input type="date" id="reservationDate" name="reservationDate" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="Enter delivery request date" value="{{$order->deliveryRequestDate}}" readonly>
          </div>
          @error('reservationDate')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Reservation Request Time -->
        <div class="tw-mb-4">
          <label for="reservationTime" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Reservation Request Time<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <input type="time" id="reservationTime" name="reservationTime" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs" placeholder="Enter delivery request time" required>
          </div>
          @error('reservationTime')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Vehicle Type -->
        <div class="tw-mb-4">
          <label for="vehicle_type" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Type<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <select id="vehicle_type" name="vehicle_type" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs" required>
              <option value="" disabled selected>Select vehicle type</option>
              <option value="light">Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)</option>
              <option value="medium">Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)</option>
              <option value="heavy">Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)</option>
            </select>
          </div>
          @error('vehicle_type')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Pick Up Location -->
        <div class="tw-mb-4">
          <label for="pickUpLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Pick Up Location<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <input type="text" id="pickUpLocation" name="pickUpLocation" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs" placeholder="Enter pick up location" required>
          </div>
          @error('pickUpLocation')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
          <div id="suggestions"></div>
        </div>

        <!-- Drop Off Location -->
        <div class="tw-mb-4">
          <label for="dropOffLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Drop Off Location<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <input type="text" id="dropOffLocation" name="dropOffLocation" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="Enter drop off location" value="{{ $order->deliveryAddress }}" readonly>
          </div>
          @error('dropOffLocation')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>
      </div>

      <!-- Submit Registration Button -->
      <div class="tw-my-6 tw-flex tw-justify-end | max-md:tw-my-2">
        <button type="submit" id="submitBtn" class=" tw-bg-indigo-600 tw-text-white tw-px-6 tw-py-2 tw-mb-2 tw-rounded-md tw-shadow-md hover:tw-bg-indigo-700 | max-md:tw-text-sm">Submit</button>
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
  
  <script>
    $(document).ready(function() {
      $("#pickUpLocation").on("input", function() {
        let query = $(this).val();

        if (query.length > 2) { // Start searching after 2 characters
          $.ajax({
            url: "/geocode/autocomplete/" + query,
            type: "GET",
            success: function(data) {
              let suggestionsBox = $("#suggestions");
              suggestionsBox.empty().show();

              if (data.features) {
                data.features.forEach(function(item) {
                  let placeName = item.properties.label;
                  suggestionsBox.append(`<div class="suggestion-item">${placeName}</div>`);

                  $(".suggestion-item").on("click", function() {
                    $("#pickUpLocation").val($(this).text());
                    suggestionsBox.hide();
                  });
                });
              }
            }
          });
        } else {
          $("#suggestions").hide();
        }
      });

      $(document).click(function(e) {
        if (!$(e.target).closest("#suggestions, #deliveryAddress").length) {
          $("#suggestions").hide();
        }
      });
    });
  </script>
</x-layout.mainTemplate>