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

    <!-- Notice -->
    @if (Auth::user()->two_factor_enabled === 0)
    <div class="tw-bg-yellow-100 tw-border tw-border-yellow-400 tw-p-4 tw-rounded tw-mb-4 | max-sm:tw-py-2">
      <p class="tw-text-sm tw-text-yellow-700 tw-font-semibold | max-sm:tw-text-[12px]">
        <i class="fa-solid fa-circle-info tw-mr-1"></i>
        <span class="tw-font-bold">Notice:</span> You have not enabled Two-Factor Authentication yet. <a href="{{ route('settings.index') }}" class="tw-underline">Click here</a> to enable it now.
      </p>
    </div>
    @endif

    <!-- Announcement -->
    <div class="tw-max-w-9xl tw-mx-auto tw-my-6 tw-bg-white tw-rounded-lg tw-shadow-lg tw-p-8 | max-sm:tw-p-4"
      data-aos="fade">
      <div>
        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-600 tw-mb-4  | max-sm:tw-mb-2 max-sm:tw-text-[16px]">Announcement:</h3>
        <p class="tw-text-sm tw-text-gray-500 tw-indent-14 max-sm:tw-text-[12px] max-sm:tw-text-justify max-sm:tw-indent-5">
          Welcome, {{ Auth::user()->firstName }}!, We're glad you're here! We're excited to announce that we've released a new feature to help you manage your orders more efficiently! You can now view your order statistics over the past 12 months and get insights on how to improve your business. Check it out and let us know what you think!
        </p>
      </div>
    </div>

    <!-- Top 5 Most Ordered Products -->
    <div class="tw-bg-[#212529] tw-rounded-lg tw-my-6 tw-shadow-lg tw-p-4 | max-sm:tw-p-2">
      <p class="tw-text-sm tw-text-white tw-mb-4 tw-font-semibold | max-sm:tw-text-[12px] max-sm:tw-text-center max-sm:tw-my-2">
        Top 5 Most Ordered Products This Month:
      </p>
      <div>
        <canvas id="demandProductChart" width="500" height="100"></canvas>
        <script>
          document.addEventListener("DOMContentLoaded", function() {
            const productNames = @json($productNames);
            const orderCounts = @json($orderCounts);

            function getRandomLightColor() {
              const r = Math.floor(Math.random() * 156) + 100;
              const g = Math.floor(Math.random() * 156) + 100;
              const b = Math.floor(Math.random() * 156) + 100;
              return `rgba(${r}, ${g}, ${b}, 0.7)`;
            }

            const backgroundColors = productNames.map(() => getRandomLightColor());

            const ctx = document.getElementById('demandProductChart');
            if (ctx) {
              const data = {
                labels: productNames,
                datasets: [{
                  label: 'Product Order Count',
                  data: orderCounts,
                  borderColor: "white",
                  backgroundColor: backgroundColors,
                }]
              };

              const config = {
                type: 'line',
                data: data,
                options: {
                  responsive: true,
                  maintainAspectRatio: true,
                  plugins: {
                    title: {
                      display: true,
                      text: 'Product Order Chart'
                    }
                  },
                }
              };

              new Chart(ctx.getContext('2d'), config);
            } else {
              console.error("Canvas element with ID 'demandProductChart' not found!");
            }
          });
        </script>
      </div>
    </div>

    <div class="tw-grid tw-grid-cols-2 tw-gap-4 ">
      <div class="tw-bg-white tw-rounded-lg tw-mb-6 tw-shadow-lg tw-p-4 | max-sm:tw-p-2">
        <p class="tw-text-sm tw-text-black tw-mb-4 tw-font-semibold | max-sm:tw-text-[12px] max-sm:tw-text-center max-sm:tw-my-2">
          Vehicle Reservations This Month:
        </p>
        <table class="datatable tw-w-full tw-bg-white  tw-rounded-md tw-shadow-md tw-my-4 tw-h-[calc(100vh-40rem)] tw-overflow-y-scroll  | max-sm:tw-text-sm">
          <thead class="tw-bg-gray-700 tw-text-white">
            <tr>
              <th class="tw-px-4 tw-py-2">Reservation Number</th>
              <th class="tw-px-4 tw-py-2">Reservation Date</th>
              <th class="tw-px-4 tw-py-2"> Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($vehicleReservations as $reservation)
            <tr>
              <td class="tw-px-4 tw-py-2">{{ $reservation->reservationNumber }}</td>
              <td class="tw-px-4 tw-py-2">{{ $reservation->reservationDate }}</td>
              <td class="tw-px-4 tw-py-2">{{ ucfirst($reservation->approval_status) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="tw-bg-white tw-rounded-lg tw-mb-6 tw-shadow-lg tw-p-4 | max-sm:tw-p-2">

        <div class="tw-mb-6 tw-h-[calc(100vh-25rem)] tw-overflow-y-scroll ">
          <p class="tw-text-sm tw-text-black tw-mb-4 tw-font-semibold | max-sm:tw-text-[12px] max-sm:tw-text-center max-sm:tw-my-2">
            Intrusion Attempts Log:
          </p>
          @foreach ($fraud as $item)
          <div class="tw-bg-gray-700 tw-shadow-md tw-rounded-lg tw-p-4 tw-mb-3">

            <div class="tw-flex tw-justify-between">
              <p class="tw-text-sm tw-text-white">{{ $item->created_at->format('Y-m-d') }}</p>
              <p class="tw-text-sm tw-text-white">{{ $item->created_at->format('h:i A') }}</p>
            </div>
            <p class="tw-text-white"><span class="tw-text-sm tw-text-white">({{ $item->user->firstName }} {{ $item->user->lastName }}):</span> {{ $item->event }} </p>
          </div>
          @endforeach
          @if ($fraud->count() == 0)
          <p class="tw-text-white tw-text-center tw-mt-4">Nothing detected</p>
          @endif
        </div>

      </div>
    </div>

    <div class="tw-bg-white tw-rounded-lg tw-shadow-lg tw-p-4 | max-sm:tw-p-2 max-sm:tw-text-sm">
      <p class="tw-text-sm tw-text-gray-500 tw-mb-4 tw-font-semibold | max-sm:tw-text-[12px]  max-sm:tw-text-center max-sm:tw-my-2">Below is a list of product records in this month:</p>
      <div class="card-body tw-px-4">
        <div class="tw-overflow-x-auto ">
          <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4">

            <thead class="tw-bg-gray-200 tw-text-gray-700 ">
              <tr>
                <th class="tw-px-4 tw-py-2">Name</th>
                <th class="tw-px-4 tw-py-2">Description</th>
                <th class="tw-px-4 tw-py-2">Stock</th>
                <th class="tw-px-4 tw-py-2">Price</th>
                <th class="tw-px-4 tw-py-2">Weight</th>
              </tr>
            </thead>

            <tbody id="orderRecords" class="tw-bg-white">
              @foreach ($product as $item)
              <tr>
                <td class="tw-px-4 tw-py-2">{{ $item->name }}</td>
                <td class="tw-px-4 tw-py-2">{{ $item->description }}</td>
                <td class="tw-px-4 tw-py-2">{{ $item->stock }}</td>
                <td class="tw-px-4 tw-py-2">&#x20B1;{{ number_format($item->price, 2) }}</td>
                <td class="tw-px-4 tw-py-2">{{ number_format($item->weight, 2) }} kg</td>
              </tr>
              @endforeach
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</x-layout.dashboardTemplate>