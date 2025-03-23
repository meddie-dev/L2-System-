<x-layout.portal.mainTemplate>
  <nav class="tw-flex | max-sm:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : (Auth::user()->hasRole('Vendor') ? 'vendorPortal.dashboard' : 'staff.dashboard'))) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('vendorPortal.order') }}" :active="false" :isLast="false">
        Vehicle Reservation
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Edit Reservation (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $vehicleReservation->reservationNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="tw-px-4">
    <p class="tw-text-sm tw-text-gray-500"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>
    <form action="{{ route('vendorPortal.vehicleReservation.update', $vehicleReservation->id) }}" enctype="multipart/form-data" method="POST" class="tw-mt-6">
      @csrf
      @method('PATCH')
      <div class="tw-grid tw-grid-cols-2 tw-gap-3 tw-text-sm | max-md:tw-grid-cols-1 max-md:tw-gap-2 ">
        <!-- Reservation Number -->
        <div class="tw-mb-3">
          <label for="reservationNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Reservation Number<span class="tw-text-red-500">*</span></label>
          <input type="text" id="reservationNumber" name="reservationNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('reservationNumber') is-invalid @enderror" placeholder="ENTER ORDER NUMBER" value="{{ $vehicleReservation->reservationNumber }}" readonly>
          @error('reservationNumber')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Reservation Request Date -->
        <div class="tw-mb-3">
          <label for="reservationDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Reservation Request Date<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <input type="date" id="reservationDate" name="reservationDate" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('reservationDate') is-invalid @enderror" value="{{ $vehicleReservation->reservationDate }}" placeholder="Enter delivery request date" required>
          </div>
          @error('reservationDate')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Reservation Request Time -->
        <div class="tw-mb-3">
          <label for="reservationTime" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Reservation Request Time<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <input type="time" id="reservationTime" name="reservationTime" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('reservationTime') is-invalid @enderror" value="{{ $vehicleReservation->reservationTime }}" placeholder="Enter delivery request time" required>
          </div>
          @error('reservationTime')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Vehicle Type -->
        <div class="tw-mb-3">
          <label for="vehicle_type" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle Type<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <select id="vehicle_type" name="vehicle_type" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('vehicle_type') is-invalid @enderror" value="{{ $vehicleReservation->vehicle_type }}" required>
              <option value="">Select vehicle type</option>
              <option value="light">Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)</option>
              <option value="medium">Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)</option>
              <option value="heavy">Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)</option>
            </select>
          </div>
          @error('vehicle_type')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Purpose -->
        <div class="tw-mb-3">
          <label for="purpose" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Purpose<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <input type="text" id="purpose" name="purpose" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('purpose') is-invalid @enderror" value="{{ $vehicleReservation->purpose }}" placeholder="Enter purpose" required>
          </div>
          @error('purpose')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Pick Up Location -->
        <div class="tw-mb-3">
          <label for="pickUpLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Pick Up Location<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <input type="text" id="pickUpLocation" name="pickUpLocation" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('pickUpLocation') is-invalid @enderror" value="{{ $vehicleReservation->pickUpLocation }}" placeholder="Enter pick up location" required>
          </div>
          @error('pickUpLocation')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
          <div id="suggestions"></div>
        </div>

        <!-- Drop Off Location -->
        <div class="tw-mb-3">
          <label for="dropOffLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Drop Off Location<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <input type="text" id="dropOffLocation" name="dropOffLocation" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('dropOffLocation') is-invalid @enderror" value="{{ $vehicleReservation->dropOffLocation }}" placeholder="Enter drop off location" required>
          </div>
          @error('dropOffLocation')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
          <div id="suggestionsDrop"></div>
        </div>
      </div>

      <!-- Submit Registration Button -->
      <div class="tw-my-6 tw-flex tw-justify-end">
        <button type="submit" class=" tw-bg-indigo-600 tw-text-white tw-px-6 tw-py-2 tw-mb-2 tw-rounded-md tw-shadow-md hover:tw-bg-indigo-700">Update</button>
      </div>
    </form>
    <hr>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2">Review After Submission</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2">
        <p class="tw-mb-1">Once the order request is submitted, you will receive an email notification. Please check your email for further instructions. The Staff will review your order request and provide you with a response within 24 hours or as soon as possible.</p>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const datepicker = document.getElementById("reservationDate");

      datepicker.addEventListener("input", function() {
        const selectedDate = new Date(this.value);
        const day = selectedDate.getDay();

        if (day === 0 || day === 6) { // 0 = Sunday, 6 = Saturday
          this.setCustomValidity("Weekends are not allowed. Please select a weekday.");
          this.reportValidity();
          this.value = ""; // Reset the selected date
        }
      });

      // Function to disable weekends in the date picker
      function setValidDates() {
        let today = new Date();
        let year = today.getFullYear();
        let month = (today.getMonth() + 1).toString().padStart(2, "0");
        let day = today.getDate().toString().padStart(2, "0");
        let minDate = `${year}-${month}-${day}`;

        datepicker.setAttribute("min", minDate);
      }

      setValidDates(); // Initialize the date picker
    });

    document.addEventListener("DOMContentLoaded", function() {
      const timepicker = document.getElementById("reservationTime");

      timepicker.addEventListener("change", function() {
        const selectedTime = this.value;
        const minTime = "09:00"; // 9:00 AM
        const maxTime = "21:00"; // 9:00 PM

        if (selectedTime < minTime || selectedTime > maxTime) {
          this.setCustomValidity("Please select a time between 9:00 AM and 9:00 PM.");
          this.reportValidity();
          this.value = ""; // Reset invalid time selection
        }
      });
    });

     // $(document).ready(function() {
    //   $("#pickUpLocation").on("input", function() {
    //     let query = $(this).val();

    //     if (query.length > 2) { // Start searching after 2 characters
    //       $.ajax({
    //         url: "/geocode/autocomplete/" + query,
    //         type: "GET",
    //         success: function(data) {
    //           let suggestionsBox = $("#suggestions");
    //           suggestionsBox.empty().show();

    //           if (data.features) {
    //             data.features.forEach(function(item) {
    //               let placeName = item.properties.label;
    //               suggestionsBox.append(`<div class="suggestion-item">${placeName}</div>`);

    //               $(".suggestion-item").on("click", function() {
    //                 $("#pickUpLocation").val($(this).text());
    //                 suggestionsBox.hide();
    //               });
    //             });
    //           }
    //         }
    //       });
    //     } else {
    //       $("#suggestions").hide();
    //     }
    //   });

    //   $(document).click(function(e) {
    //     if (!$(e.target).closest("#suggestions, #deliveryAddress").length) {
    //       $("#suggestions").hide();
    //     }
    //   });
    // });

    // $(document).ready(function() {
    //   $("#dropOffLocation").on("input", function() {
    //     let query = $(this).val();

    //     if (query.length > 2) { // Start searching after 2 characters
    //       $.ajax({
    //         url: "/geocode/autocomplete/" + query,
    //         type: "GET",
    //         success: function(data) {
    //           let suggestionsBox = $("#suggestionsDrop");
    //           suggestionsBox.empty().show();

    //           if (data.features) {
    //             data.features.forEach(function(item) {
    //               let placeName = item.properties.label;
    //               suggestionsBox.append(`<div class="suggestion-item">${placeName}</div>`);

    //               $(".suggestion-item").on("click", function() {
    //                 $("#dropOffLocation").val($(this).text());
    //                 suggestionsBox.hide();
    //               });
    //             });
    //           }
    //         }
    //       });
    //     } else {
    //       $("#suggestions").hide();
    //     }
    //   });

    //   $(document).click(function(e) {
    //     if (!$(e.target).closest("#suggestions, #deliveryAddress").length) {
    //       $("#suggestions").hide();
    //     }
    //   });
    // });
  </script>
</x-layout.portal.mainTemplate>