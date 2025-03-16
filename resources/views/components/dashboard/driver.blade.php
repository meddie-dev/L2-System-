<x-layout.dashboardTemplate>
  <div class="container-fluid tw-my-10 tw-px-4 | max-sm:tw-px-0 max-sm:tw-my-5">
    <nav class="tw-flex tw-mb-5 | max-sm:flex-start" aria-label="Breadcrumb">
      <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse">
        <x-partials.breadcrumb href="{{ (Auth::user()->hasRole('Super Admin')) ? '/superadmin/dashboard' : ((Auth::user()->hasRole('Admin')) ? '/admin/dashboard' : '/staff/dashboard') }}" :active="true" :isLast="true">
          <div class="sb-nav-link-icon tw-pr-2 | max-sm:tw-text-sm"><i class="fa-solid fa-table-columns | max-sm:tw-text-sm"></i>
            Dashboard
          </div>
        </x-partials.breadcrumb>
      </ol>
    </nav>

    @if (Auth::user()->two_factor_enabled === 0)
    <div class="tw-bg-yellow-100 tw-border tw-border-yellow-400 tw-p-4 tw-rounded tw-mb-4 | max-sm:tw-py-2">
      <p class="tw-text-sm tw-text-yellow-700 tw-font-semibold | max-sm:tw-text-[12px]">
        <i class="fa-solid fa-circle-info tw-mr-1"></i>
        <span class="tw-font-bold">Notice:</span> You have not enabled Two-Factor Authentication yet. <a href="{{ route('settings.index') }}" class="tw-underline">Click here</a> to enable it now.
      </p>
    </div>
    @endif

    @if (Auth::user()->driverType === null)
    <div class="tw-bg-yellow-100 tw-border tw-border-yellow-400 tw-p-4 tw-rounded tw-mb-4 | max-sm:tw-py-2">
      <p class="tw-text-sm tw-text-yellow-700 tw-font-semibold | max-sm:tw-text-[12px]">
        <i class="fa-solid fa-circle-info tw-mr-1"></i>
        <span class="tw-font-bold">Notice:</span> You have not set your Driver Type yet. You cannot receive any assigned transaction until you set it. <a href="{{ route('settings.index') }}" class="tw-underline">Click here</a> to set it now.
      </p>
    </div>
    @endif

    <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-mb-4 | max-md:tw-grid-cols-1">
      <!-- Performance Score-->
      <div class="tw-bg-white tw-rounded-lg tw-shadow-lg tw-p-4 | max-md:tw-p-2">
        <div class="tw-flex tw-items-center tw-justify-between tw-mb-2">
          <p class="tw-text-sm tw-font-semibold tw-text-gray-500 tw-mb-1 | max-sm:tw-text-[12px]">Performance Score</p>
          <p class="tw-text-sm tw-font-semibold tw-flex tw-items-center | max-sm:tw-text-[12px]">
            {{ number_format($user->performance_score) }}%
            @if ($user->performance_score <= 60)
              <i class="fa-solid fa-arrow-down text-blue-500 tw-ml-1"></i>
              @else
              <i class="fa-solid fa-arrow-up text-blue-500 tw-ml-1"></i>
              @endif
          </p>
        </div>
        <div class="chart-container tw-bg-white">
          <canvas id="userChart" width="400" height="200"></canvas>
        </div>
      </div>

      <!-- Delivery Performance -->
      <div class="tw-flex tw-flex-col tw-justify-between | max-md:tw-gap-4">
        <div class="tw-bg-white tw-rounded-lg  tw-shadow-lg tw-p-4 tw-items-center | max-sm:tw-p-2">
          @php
          $totalDeliveries = $user->on_time_deliveries + $user->late_deliveries + $user->early_deliveries;
          $onTimePercentage = $totalDeliveries > 0 ? number_format(($user->on_time_deliveries / $totalDeliveries) * 100, 2) : '0.00';
          $latePercentage = $totalDeliveries > 0 ? number_format(($user->late_deliveries / $totalDeliveries) * 100, 2) : '0.00';
          $earlyPercentage = $totalDeliveries > 0 ? number_format(($user->early_deliveries / $totalDeliveries) * 100, 2) : '0.00';
          @endphp

          <div class="tw-overflow-x-auto tw-rounded-lg tw-border  tw-border-gray-200 tw-p-[25px]">
            <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-mt-3 ">
              <div class="tw-flex tw-flex-col tw-items-center tw-justify-between">
                <p class="tw-text-sm tw-font-semibold tw-text-gray-600 tw-flex tw-items-center | max-md:tw-text-[12px] ">
                  <i class="fa-solid fa-clock tw-mr-1"></i>On Time ({{ $onTimePercentage }}%)
                </p>
                <p class="tw-text-sm tw-font-semibold tw-text-blue-500 tw-flex tw-items-center">
                  <i class="fa-solid fa-check tw-mr-1"></i>{{ $user->on_time_deliveries }}
                </p>
              </div>
              <div class="tw-flex tw-flex-col tw-items-center tw-justify-between">
                <p class="tw-text-sm tw-font-semibold tw-text-gray-600 tw-flex tw-items-center | max-md:tw-text-[12px]">
                  <i class="fa-solid fa-clock tw-mr-1"></i>Late ({{ $latePercentage }}%)
                </p>
                <p class="tw-text-sm tw-font-semibold tw-text-blue-500 tw-flex tw-items-center">
                  <i class="fa-solid fa-times tw-mr-1"></i>{{ $user->late_deliveries }}
                </p>
              </div>
              <div class="tw-flex tw-flex-col tw-items-center tw-justify-between">
                <p class="tw-text-sm tw-font-semibold tw-text-gray-600 tw-flex tw-items-center | max-md:tw-text-[12px]">
                  <i class="fa-solid fa-clock tw-mr-1"></i>Early ({{ $earlyPercentage }}%)
                </p>
                <p class="tw-text-sm tw-font-semibold tw-text-blue-500 tw-flex tw-items-center">
                  <i class="fa-solid fa-check tw-mr-1"></i>{{ $user->early_deliveries }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Average Rating -->
        <div class="tw-bg-white tw-rounded-lg  tw-shadow-lg tw-p-4 tw-items-center | max-sm:tw-p-2">
          <div class="tw-overflow-x-auto tw-rounded-lg tw-border tw-border-gray-200 tw-p-[25px]">
            <div class="tw-flex tw-flex-col tw-justify-center ">

              <div class="tw-flex tw-items-center tw-justify-between">
                <p class="tw-text-sm tw-font-semibold tw-text-gray-500 tw-mb-1 | max-sm:tw-text-[12px]">Average Rating</p>
                <p class="tw-text-sm tw-font-semibold | max-sm:tw-text-[12px]">{{ number_format($user->tripTickets()->avg('rating'), 1) }} <span style="color: #3490dc;">&#9733;</span></p>
              </div>
              <div class="tw-mb-1   tw-text-center">
                <span class="tw-text-3xl tw-font-bold tw-inline-block tw-text-gray-500">
                  @for ($i = 0; $i < 5; $i++)
                    @if ($i < floor($user->tripTickets()->avg('rating')))
                    <span style="color: #3490dc;">&#9733;</span>
                    @else
                    <span style="color: #6c757d;">&#9734;</span>
                    @endif
                    @endfor
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="tw-bg-white tw-rounded-lg tw-mb-4 tw-shadow-lg tw-p-4 | max-sm:tw-p-2">
      <div class="tw-mb-6 tw-rounded-2xl" id="map"></div>
      <div class="tw-grid tw-grid-cols-2 tw-gap-4 | max-md:tw-grid-cols-1">
        <!-- Latest Trip -->
        <div class="tw-overflow-x-auto tw-rounded-lg tw-border tw-border-gray-200 tw-p-4 ">
          <div>
            <h3 class="tw-text-lg tw-font-semibold tw-text-gray-600 tw-mb-4  | max-sm:tw-mb-2 max-sm:tw-text-[16px]">Latest Trip Details</h3>
            <div class="tw-grid tw-grid-cols-2 tw-gap-4">
              <div>
                <p class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-mb-1 | max-sm:tw-text-[12px]">Trip Number</p>
                <a class="tw-text-xs tw-font-semibold tw-text-blue-600 | max-sm:tw-text-[12px]" href="{{ $latestTripTicket ? route('driver.trip.details', $latestTripTicket->id) : '#' }}" class="tw-text-xs tw-font-semibold tw-underline | max-sm:tw-text-[12px]">{{ $latestTripTicket ? substr($latestTripTicket->tripNumber, 0, 9) . '...' : '-' }}</a>
              </div>
              <div>
                <p class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-mb-1 | max-sm:tw-text-[12px]">Departure Date</p>
                <p class="tw-text-xs tw-font-semibold | max-sm:tw-text-[12px]">{{ $latestTripTicket ? \Carbon\Carbon::parse($latestTripTicket->departureTime)->format('F j, Y') : '-' }}</p>
              </div>

              <div>
                <p class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-mb-1 | max-sm:tw-text-[12px]">Departure Time</p>
                <p class="tw-text-xs tw-font-semibold | max-sm:tw-text-[12px]">{{ $latestTripTicket ? \Carbon\Carbon::parse($latestTripTicket->departureTime)->format('g:i A') : '-' }}</p>
              </div>
              <div>
                <p class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-mb-1 | max-sm:tw-text-[12px]">Arrival Date</p>
                <p class="tw-text-xs tw-font-semibold | max-sm:tw-text-[12px]">{{ $latestTripTicket ? \Carbon\Carbon::parse($latestTripTicket->arrivalTime)->format('F j, Y') : '-' }}</p>
              </div>
              <div>
                <p class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-mb-1 | max-sm:tw-text-[12px]">Arrival Time</p>
                <p class="tw-text-xs tw-font-semibold | max-sm:tw-text-[12px]">{{ $latestTripTicket ? \Carbon\Carbon::parse($latestTripTicket->arrivalTime)->format('g:i A') : '-' }}</p>
              </div>

              <div>
                <p class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-mb-1 | max-sm:tw-text-[12px]">Distance</p>
                <p class="tw-text-xs tw-font-semibold | max-sm:tw-text-[12px]">{{ $latestTripTicket ? number_format($latestTripTicket->distance, 3) . ' km' : '-' }}</p>
              </div>
              <div>
                <p class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-mb-1 | max-sm:tw-text-[12px]">Duration</p>
                @php
                $totalMinutes = $latestTripTicket ? $latestTripTicket->duration : 0;
                $hours = floor($totalMinutes / 60);
                $minutes = floor($totalMinutes % 60);
                $seconds = round(($totalMinutes - floor($totalMinutes)) * 60);
                @endphp
                <p class="tw-text-xs tw-font-semibold | max-sm:tw-text-[12px]">{{ sprintf('%d hour%s %d minute%s %d second%s', $hours, $hours == 1 ? '' : 's', $minutes, $minutes == 1 ? '' : 's', $seconds, $seconds == 1 ? '' : 's') }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Activity Logs -->
        <div class="tw-overflow-y-auto tw-max-h-[calc(100vh-23rem)] tw-rounded-lg tw-border tw-border-gray-200 tw-p-4 tw-row-span-2 tw-mb-4">
          <h3 class="tw-text-lg tw-font-semibold tw-text-gray-600 tw-mb-4 | max-sm:tw-mb-2 max-sm:tw-text-[16px]">Activity Logs</h3>
          <div class="tw-container tw-mx-auto tw-p-6">
            <div class="tw-relative tw-border-l-4 tw-border-[#212529] tw-ml-1">
              @foreach ($logs as $log)
              <div class="tw-mb-6 tw-ml-6">
                <div class="tw-absolute tw-w-4 tw-h-4 tw-bg-[#212529] tw-rounded-full tw--left-[10px] tw-mt-8"></div>
                <div class="tw-bg-white tw-shadow-md tw-rounded-lg tw-p-4">
                  <div class="tw-flex tw-justify-between">
                    <p class="tw-text-sm tw-text-gray-600">{{ $log->created_at->format('M d, Y') }}</p>
                    <p class="tw-text-sm tw-text-gray-600">{{ $log->created_at->format('H:i') }}</p>
                  </div>
                  <p class="tw-text-gray-700">{{ $log->event }}</p>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const map = L.map('map').setView([51.505, -0.09], 13);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      const performanceScore = {{
          $user->performance_score
        }};
      const scoreCategory = performanceScore > 60 ? ['Good', 'Bad'] : ['Bad', 'Good'];

      const ctx = document.getElementById('userChart');
      if (ctx) {
        const userChart = new Chart(ctx.getContext('2d'), {
          type: 'doughnut',
          data: {
            labels: scoreCategory,
            datasets: [{
              label: 'Driver Performance',
              data: [performanceScore, 100 - performanceScore],
              backgroundColor: ['rgba(33, 37, 41, 0.2)', 'rgba(255, 99, 132, 0.2)'],
              borderColor: ['rgba(33, 37, 41, 1)', 'rgba(255, 99, 132, 1)'],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'top',
              },
            },
          }
        });
      } else {
        console.error("Canvas element not found!");
      }

      // @if($latestTripTicket && $latestVehicleReservation)
      // const pickUpLat = {{ $latestTripTicket->pickUpLat ?? 51.505 }};
      // const pickUpLng = {{ $latestTripTicket->pickUpLng ?? -0.09 }};
      // const dropOffLat = {{ $latestTripTicket->dropOffLat ?? 51.515 }};
      // const dropOffLng = {{ $latestTripTicket->dropOffLng ?? -0.1 }};

      // // Pickup Marker
      // const pickUpMarker = L.marker([pickUpLat, pickUpLng]).addTo(map);
      // pickUpMarker.bindPopup("<b>Pick Up Location</b><br>{{ $latestVehicleReservation->pickUpLocation }}");

      // // Drop-off Marker
      // const dropOffMarker = L.marker([dropOffLat, dropOffLng]).addTo(map);
      // dropOffMarker.bindPopup("<b>Drop Off Location</b><br>{{ $latestVehicleReservation->dropOffLocation }}");

      // // Adjust map to fit both markers
      // const bounds = new L.LatLngBounds([
      //   [pickUpLat, pickUpLng],
      //   [dropOffLat, dropOffLng]
      // ]);
      // map.fitBounds(bounds);

      // Fetch and display route
      // const apiKey = "{{ env('OPENROUTESERVICE_API_KEY') }}";
      // const request = new XMLHttpRequest();
      // request.open('GET', `https://api.openrouteservice.org/v2/directions/driving-car?api_key=${apiKey}&start=${pickUpLng},${pickUpLat}&end=${dropOffLng},${dropOffLat}`, true);
      // request.setRequestHeader('Accept', 'application/json, application/geo+json, application/gpx+xml, img/png; charset=utf-8');

      // request.onreadystatechange = function() {
      //   if (this.readyState === 4 && this.status === 200) {
      //     const routeCoordinates = JSON.parse(this.responseText).features[0].geometry.coordinates;
      //     const routeLine = L.polyline(routeCoordinates.map(coord => [coord[1], coord[0]]), {
      //       color: 'red',
      //       weight: 5,
      //       opacity: 0.7
      //     }).addTo(map);
      //     map.fitBounds(routeLine.getBounds());
      //   } else if (this.readyState === 4 && this.status !== 200) {
      //     console.error('Error fetching the route:', this.statusText);
      //   }
      // };
      // request.send();
      @endif
    });
  </script>

</x-layout.dashboardTemplate>