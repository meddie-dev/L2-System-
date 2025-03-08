<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between |  max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'driver.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('driver.task') }}" :active="false" :isLast="false">
        Task Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Review Ticket Number (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $tripTicket->tripNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-mb-6" id="map"></div>
    <div class='tw-flex tw-justify-between tw-items-center tw-my-2'>
      <h3 class="tw-text-md tw-font-bold  | max-md:tw-text-sm">Trip Ticket</h3>
      <div class="tw-flex">
        <a href="{{ route('driver.trip') }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-white  tw-bg-red-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-red-600 hover:tw-bg-white  hover:tw-text-red-600 | max-md:tw-p-3 ">
          <i class="fa-solid fa-exclamation-circle tw-mr-2 | max-md:tw-text-xs"></i>
          Report Incident
        </a>
      </div>
    </div>
    <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-px-4 tw-text-sm tw-mb-4 | max-md:tw-text-xs max-md:tw-grid-cols-1 max-md:tw-gap-2">
      <!-- Trip Number -->
      <div>
        <label for="tripNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Trip Number:</label>
        <input type="text" id="tripNumber" name="tripNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ $tripTicket->tripNumber }}" readonly>
      </div>

      <!-- Destination -->
      <div>
        <label for="destination" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Destination:</label>
        <input type="text" id="destination" name="destination" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ $tripTicket->destination }}" readonly>
      </div>

      <!-- Departure -->
      <div>
        <label for="departureDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Departure Date:</label>
        <input type="text" id="departureDate" name="departureDate" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ \Carbon\Carbon::parse($tripTicket->departureTime)->format('F j, Y') }}" readonly>
      </div>
      <div>
        <label for="departureTime" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Departure Time:</label>
        <input type="text" id="departureTime" name="departureTime" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ \Carbon\Carbon::parse($tripTicket->departureTime)->format('g:i a') }}" readonly>
      </div>

      <!-- Arrival -->
      <div>
        <label for="arrivalDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Arrival Date:</label>
        <input type="text" id="arrivalDate" name="arrivalDate" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ \Carbon\Carbon::parse($tripTicket->arrivalTime)->format('F j, Y') }}" readonly>
      </div>
      <div>
        <label for="arrivalTime" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Arrival Time:</label>
        <input type="text" id="arrivalTime" name="arrivalTime" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ \Carbon\Carbon::parse($tripTicket->arrivalTime)->format('g:i a') }}" readonly>
      </div>

      <!-- Allocated Fuel -->
      <div>
        <label for="allocatedFuel" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Allocated Fuel:</label>
        <input type="text" id="allocatedFuel" name="allocatedFuel" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="PHP {{ number_format($tripTicket->allocatedFuel, 2) }}" readonly>
      </div>

      <!-- Status -->
      <div>
        <label for="status" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Status:</label>
        <input type="text" id="status" name="status" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ str_replace('_', ' ', ucfirst($tripTicket->status)) }}" readonly>
      </div>

      <!-- Distance -->
      <div>
        <label for="distance" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Distance:</label>
        <input type="text" id="distance" name="distance" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ number_format($tripTicket->distance, 3)}} km" readonly>
      </div>

      <!-- Duration -->
      <div>
        <label for="duration" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Duration:</label>
        @php
        $totalMinutes = $tripTicket->duration;
        $hours = floor($totalMinutes / 60);
        $minutes = floor($totalMinutes % 60);
        $seconds = round(($totalMinutes - floor($totalMinutes)) * 60);
        @endphp
        <input type="text" id="duration" name="duration" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ sprintf('%d hour%s %d minute%s %d second%s', $hours, $hours == 1 ? '' : 's', $minutes, $minutes == 1 ? '' : 's', $seconds, $seconds == 1 ? '' : 's') }}" readonly>
      </div>
    </div>

    @if($tripTicket->status === 'delivered')
    <!-- Feedback and Rating  -->
    <h3 class="tw-text-md tw-font-bold tw-my-2 | max-md:tw-text-sm">Feedback and Rating</h3>
    <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-grid-cols-1 max-md:tw-gap-2">
      <!-- Rating -->
      <div>
        <label for="rating" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Rating:</label>
        <div class="tw-flex tw-items-center">
          @for ($i = 0; $i < 5; $i++)
            <svg class="tw-w-5 tw-h-5 tw-mr-1 tw-fill-current{{ $i < $tripTicket->rating ? ' tw-text-yellow-500' : ' tw-text-gray-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.959a1 1 0 00.95.69h4.164c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.36 1.118l1.286 3.96c.3.92-.755 1.688-1.54 1.118l-3.36-2.44a1 1 0 00-1.176 0l-3.36 2.44c-.785.57-1.838-.197-1.54-1.118l1.286-3.96a1 1 0 00-.36-1.118L2.103 9.386c-.782-.57-.38-1.81.588-1.81h4.164a1 1 0 00.95-.69l1.286-3.959z" />
            </svg>
            @endfor
            <span class="tw-text-sm tw-font-bold tw-text-gray-500 | max-md:tw-text-xs">({{ $tripTicket->rating }} / 5)</span>
        </div>
      </div>

      <!-- Feedback -->
      <div>
        <label for="feedback" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Feedback:</label>
        <textarea id="feedback" name="feedback" rows="4" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" readonly>{{ $tripTicket->order->user->firstName }} {{ $tripTicket->order->user->lastName }}:
        {{ $tripTicket->feedback }}</textarea>
      </div>
    </div>
    @endif

  </div>
  <div class="tw-flex tw-justify-end tw-mb-6">
    @if($tripTicket->status === 'scheduled')
    <form action="{{ route('driver.trip.inTransit', $tripTicket->id) }}" method="POST">
      @csrf
      @method('PATCH')
      <button type="submit" class="tw-text-white tw-bg-gray-700 tw-text-sm tw-font-bold tw-py-2 tw-px-4 tw-rounded | max-md:tw-text-xs">Mark as In Transit</button>
    </form>
    @elseif($tripTicket->status === 'in_transit')
    <form action="{{ route('driver.trip.deliver', $tripTicket->id) }}" method="POST">
      @csrf
      @method('PATCH')
      <button type="submit" class="tw-text-white tw-bg-gray-700 tw-text-sm tw-font-bold tw-py-2 tw-px-4 tw-rounded | max-md:tw-text-xs">Mark as Delivered</button>
    </form>
    @endif
  </div>
  <div class="tw-flex tw-items-center tw-justify-start tw-my-6">
    <a href="{{ route('driver.trip') }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-p-3 ">
      <i class="fa-solid fa-arrow-left tw-mr-2 | max-md:tw-text-xs"></i>
      Back
    </a>
  </div>
  <hr>
  <div>
    <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2 | max-md:tw-text-sm">Review After Submission</h3>
    <div class="tw-text-xs tw-text-gray-600 tw-mb-2 | max-md:tw-text-[11px]">
      <p class="tw-mb-1">Once the order request is submitted, you will receive an email notification. Please check your email for further instructions. The Staff will review your order request and provide you with a response within 24 hours or as soon as possible.</p>
    </div>
  </div>

  <!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
      const map = L.map('map').setView([{{ $tripTicket->pickUpLat ?? 51.505 }}, {{ $tripTicket->pickUpLng ?? -0.09 }}], 13);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      const pickUpMarker = L.marker([{{ $tripTicket->pickUpLat ?? 51.505 }}, {{ $tripTicket->pickUpLng ?? -0.09 }}]).addTo(map);
      pickUpMarker.bindPopup("<b>Pick Up Location</b><br>{{ $vehicleReservation->pickUpLocation }}");

      const dropOffMarker = L.marker([{{ $tripTicket->dropOffLat ?? 51.515 }}, {{ $tripTicket->dropOffLng ?? -0.1 }}]).addTo(map);
      dropOffMarker.bindPopup("<b>Drop Off Location</b><br>{{ $vehicleReservation->dropOffLocation }}");

      const bounds = new L.LatLngBounds([
        [{{ $tripTicket->pickUpLat ?? 51.505 }}, {{ $tripTicket->pickUpLng ?? -0.09 }}],
        [{{ $tripTicket->dropOffLat ?? 51.515 }}, {{ $tripTicket->dropOffLng ?? -0.1 }}]
      ]);
      map.fitBounds(bounds);

      const apiKey = "{{ env('OPENROUTESERVICE_API_KEY') }}";
      const request = new XMLHttpRequest();
      request.open('GET', `https://api.openrouteservice.org/v2/directions/driving-car?api_key=${apiKey}&start={{ $tripTicket->pickUpLng }},{{ $tripTicket->pickUpLat }}&end={{ $tripTicket->dropOffLng }},{{ $tripTicket->dropOffLat }}`, true);
      request.setRequestHeader('Accept', 'application/json, application/geo+json, application/gpx+xml, img/png; charset=utf-8');
      request.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          const routeCoordinates = JSON.parse(this.responseText).features[0].geometry.coordinates;
          const routeLine = L.polyline(routeCoordinates.map(coord => [coord[1], coord[0]]), {
            color: '{{ $tripTicket->status === 'scheduled' ? '#F7DC6F' : ($tripTicket->status === 'in_transit' ? '#3498DB' : ($tripTicket->status === 'delay' ? '#E74C3C' : '#2ECC71')) }}',
            weight: 5,
            opacity: 0.7
          }).addTo(map);
          map.fitBounds(routeLine.getBounds());
        } else if (this.readyState === 4 && this.status !== 200) {
          console.error('Error fetching the route:', this.statusText);
        }
      };
      request.send();
    });
  </script> -->

</x-layout.mainTemplate>