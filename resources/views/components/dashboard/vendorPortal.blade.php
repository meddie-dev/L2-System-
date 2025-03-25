<x-layout.portal.portalDashboardTemplate>
  <div class="container-fluid | max-sm:tw-my-5 max-sm:tw-px-0 tw-my-10 tw-px-4">
    <nav class="| max-md:tw-hidden tw-flex tw-mb-5" aria-label="Breadcrumb">
      <ol class="| md:tw-space-x-2 rtl:tw-space-x-reverse tw-inline-flex tw-items-center tw-space-x-1">
        <x-partials.breadcrumb href="{{ route('vendorPortal.dashboard') }}" :active="true" :isLast="true">
          <div class="| max-sm:tw-text-sm sb-nav-link-icon tw-pr-2"><i class="| fa-solid fa-table-columns max-sm:tw-text-sm"></i>
            Dashboard
          </div>
        </x-partials.breadcrumb>
      </ol>
    </nav>

    @if (Auth::user()->two_factor_enabled === 0)
    <div class="| max-sm:tw-py-2 tw-bg-yellow-100 tw-border tw-border-yellow-400 tw-mb-4 tw-p-4 tw-rounded">
      <p class="| max-sm:tw-text-[12px] tw-font-semibold tw-text-sm tw-text-yellow-700">
        <i class="fa-circle-info fa-solid"></i>
        <span class="tw-font-bold">Notice:</span> You have not enabled Two-Factor Authentication yet. <a href="{{ route('settings.index') }}" class="tw-underline">Click here</a> to enable it now.
      </p>
    </div>
    @endif

    <div class="| max-sm:tw-my-6 max-sm:tw-p-4 tw-bg-white tw-max-w-9xl tw-mb-10 tw-mx-auto tw-p-8 tw-rounded-lg tw-shadow-lg"
      data-aos="fade">
      <div>
        <h3 class="| max-sm:tw-mb-2 max-sm:tw-text-[16px] tw-font-semibold tw-mb-4 tw-text-gray-600 tw-text-lg">Announcement:</h3>
        <p class="max-sm:tw-indent-5 max-sm:tw-text-[12px] max-sm:tw-text-justify tw-indent-14 tw-text-gray-500 tw-text-sm">
          Welcome, {{ Auth::user()->firstName }}!, We're glad you're here! We're excited to announce that we've released a new feature to help you manage your orders more efficiently! You can now view your order statistics over the past 12 months and get insights on how to improve your business. Check it out and let us know what you think!
        </p>
      </div>
    </div>

    <!-- Cards -->
    <div class="row" data-aos="fade">
      <!-- Scheduled -->
      <div class="col-md-6 col-xl-4">
        <div class="card bg-primary text-white mb-4">
          <div class="d-flex card-body align-items-center justify-content-between | max-md:tw-text-sm tw-font-bold tw-opacity-50 tw-text-2xl">
            <div>
              Scheduled
            </div>
            <span class="| max-md:tw-text-sm tw-font-bold tw-opacity-50 tw-text-2xl">{{ $statusCounts['scheduled'] }}</span>
          </div>
          <div class="d-flex card-footer align-items-center justify-content-between">
            <a class="text-white small stretched-link" href="{{ route('vendorPortal.card.scheduled') }}">View Details</a>
            <div class="text-white small"><i class="fa-angle-right fas"></i></div>
          </div>
        </div>
      </div>

      <!-- In Transit -->
      <div class="col-md-6 col-xl-4">
        <div class="card bg-primary text-white mb-4">
          <div class="d-flex card-body align-items-center justify-content-between | max-md:tw-text-sm tw-font-bold tw-opacity-50 tw-text-2xl">
            <div>
              In Transit
            </div>
            <span class="| max-md:tw-text-sm tw-font-bold tw-opacity-50 tw-text-2xl">{{ $statusCounts['in_transit'] }}</span>
          </div>
          <div class="d-flex card-footer align-items-center justify-content-between">
            <a class="text-white small stretched-link" href="{{ route('vendorPortal.card.inTransit') }}">View Details</a>
            <div class="text-white small"><i class="fa-angle-right fas"></i></div>
          </div>
        </div>
      </div>

      <!-- Delivered -->
      <div class="col-md-6 col-xl-4">
        <div class="card bg-primary text-white mb-4">
          <div class="d-flex card-body align-items-center justify-content-between | max-md:tw-text-sm tw-font-bold tw-opacity-50 tw-text-2xl">
            <div>
              Delivered
            </div>
            <span class="| max-md:tw-text-sm tw-font-bold tw-opacity-50 tw-text-2xl">{{ $statusCounts['delivered'] }}</span>
          </div>
          <div class="d-flex card-footer align-items-center justify-content-between">
            <a class="text-white small stretched-link" href="{{ route('vendorPortal.card.delivered') }}">View Details</a>
            <div class="text-white small"><i class="fa-angle-right fas"></i></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Statistics -->
    <div class="| max-sm:tw-p-2 tw-bg-white tw-mb-4 tw-p-4 tw-rounded-lg tw-shadow-lg">
      <p class="| max-sm:tw-my-2 max-sm:tw-text-[12px] max-sm:tw-text-center tw-font-semibold tw-mb-4 tw-text-gray-500 tw-text-sm">Here's a summary of your order statistics over the past 12 months:</p>
      <div>
        <canvas id="orderRoleChart" width="400" height="200"></canvas>
        <script>
          const months = @json($months);
          const orderCounts = @json($orderCounts);
          const ctx = document.getElementById('orderRoleChart').getContext('2d');
          const orderRoleChart = new Chart(ctx, {
            type: 'line',
            data: {
              labels: months,
              datasets: [{
                label: 'Orders',
                data: orderCounts,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: false,
                },
              },
            }
          });
        </script>
      </div>
    </div>

    <div class="| max-sm:tw-p-2 tw-bg-white tw-mb-4 tw-p-4 tw-rounded-lg tw-shadow-lg">
      <!-- Activity Logs -->
      <div class="tw-max-h-[calc(100vh-23rem)] tw-mb-4 tw-overflow-y-auto tw-row-span-2">
        <p class="| max-sm:tw-my-2 max-sm:tw-text-[12px] max-sm:tw-text-center tw-font-semibold tw-text-gray-500 tw-text-sm">Here's a summary of your activity logs:</p>
        <div class="tw-container tw-mx-auto tw-p-6">
          <div class="tw-border-blue-500 tw-border-l-4 tw-ml-1 tw-relative">
            @foreach ($logs as $log)
            <div class="tw-mb-6 tw-ml-6">
              <div class="tw--left-[10px] tw-absolute tw-bg-blue-500 tw-h-4 tw-mt-8 tw-rounded-full tw-w-4"></div>
              <div class="tw-bg-white tw-p-4 tw-rounded-lg tw-shadow-md">
                <div class="tw-flex tw-justify-between">
                  <p class="tw-text-gray-600 tw-text-sm">{{ $log->created_at->format('Y-m-d') }}</p>
                  <p class="tw-text-gray-600 tw-text-sm">{{ $log->created_at->format('h:i A') }}</p>
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
</x-layout.portal.portalDashboardTemplate>