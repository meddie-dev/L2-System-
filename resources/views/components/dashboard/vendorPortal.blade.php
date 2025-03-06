<x-layout.portal.portalDashboardTemplate>
  <div class="container-fluid tw-my-10 tw-px-4 | max-sm:tw-px-0 max-sm:tw-my-5">
    <nav class="tw-flex tw-mb-5 | max-md:tw-hidden" aria-label="Breadcrumb">
      <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse">
        <x-partials.breadcrumb href="{{ route('vendorPortal.dashboard') }}" :active="true" :isLast="true">
          <div class="sb-nav-link-icon tw-pr-2 | max-sm:tw-text-sm"><i class="fa-solid fa-table-columns | max-sm:tw-text-sm"></i>
            Dashboard
          </div>
        </x-partials.breadcrumb>
      </ol>
    </nav>

    @if (Auth::user()->two_factor_enabled === 0)
    <div class="tw-bg-yellow-100 tw-border tw-border-yellow-400 tw-p-4 tw-rounded tw-mb-4 | max-sm:tw-py-2">
      <p class="tw-text-sm tw-text-yellow-700 tw-font-semibold | max-sm:tw-text-[12px]">
        <i class="fa-solid fa-circle-info"></i>
        <span class="tw-font-bold">Notice:</span> You have not enabled Two-Factor Authentication yet. <a href="{{ route('settings.index') }}" class="tw-underline">Click here</a> to enable it now.
      </p>
    </div>
    @endif

    <div class="tw-max-w-9xl tw-mx-auto tw-mb-10 tw-bg-white tw-rounded-lg tw-shadow-lg tw-p-8 | max-sm:tw-p-4 max-sm:tw-my-6"
      data-aos="fade">
      <div>
        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-600 tw-mb-4  | max-sm:tw-mb-2 max-sm:tw-text-[16px]">Announcement:</h3>
        <p class="tw-text-sm tw-text-gray-500 tw-indent-14 max-sm:tw-text-[12px] max-sm:tw-text-justify max-sm:tw-indent-5">
          Welcome, {{ Auth::user()->firstName }}!, We're glad you're here! We're excited to announce that we've released a new feature to help you manage your orders more efficiently! You can now view your order statistics over the past 12 months and get insights on how to improve your business. Check it out and let us know what you think!
        </p>
      </div>
    </div>

    <!-- Cards -->
    <div class="row " data-aos="fade">
      <!-- Scheduled -->
      <div class="col-xl-4 col-md-6">
        <div class="card bg-warning text-white mb-4">
          <div class="card-body d-flex align-items-center justify-content-between tw-font-bold tw-text-2xl tw-opacity-50 | max-md:tw-text-sm ">
            <div>
              <i class="fa-solid fa-clock tw-mr-2"></i>
              Scheduled
            </div>
            <span class="tw-font-bold tw-text-2xl tw-opacity-50 | max-md:tw-text-sm ">{{ $statusCounts['scheduled'] }}</span>
          </div>
          <div class="card-footer d-flex align-items-center justify-content-between ">
            <a class="small text-white stretched-link" href="{{ route('vendorPortal.card.scheduled') }}">View Details</a>
            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
          </div>
        </div>
      </div>

      <!-- In Transit -->
      <div class="col-xl-4 col-md-6">
        <div class="card bg-primary text-white mb-4">
          <div class="card-body d-flex align-items-center justify-content-between tw-font-bold tw-text-2xl tw-opacity-50 | max-md:tw-text-sm ">
            <div>
              <i class="fa-solid fa-truck-fast"></i>
              In Transit
            </div>
            <span class="tw-font-bold tw-text-2xl tw-opacity-50 | max-md:tw-text-sm ">{{ $statusCounts['in_transit'] }}</span>
          </div>
          <div class="card-footer d-flex align-items-center justify-content-between">
            <a class="small text-white stretched-link" href="{{ route('vendorPortal.card.inTransit') }}">View Details</a>
            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
          </div>
        </div>
      </div>

      <!-- Delivered -->
      <div class="col-xl-4 col-md-6">
        <div class="card bg-success text-white mb-4">
          <div class="card-body d-flex align-items-center justify-content-between tw-font-bold tw-text-2xl tw-opacity-50 | max-md:tw-text-sm ">
            <div>
              <i class="fa-solid fa-circle-check"></i>
              Delivered
            </div>
            <span class="tw-font-bold tw-text-2xl tw-opacity-50 | max-md:tw-text-sm ">{{ $statusCounts['delivered'] }}</span>
          </div>
          <div class="card-footer d-flex align-items-center justify-content-between">
            <a class="small text-white stretched-link" href="{{ route('vendorPortal.card.delivered') }}">View Details</a>
            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Statistics -->
    <div class="tw-bg-white tw-rounded-lg tw-mb-4 tw-shadow-lg tw-p-4 | max-sm:tw-p-2">
      <p class="tw-text-sm tw-text-gray-500 tw-mb-4 tw-font-semibold | max-sm:tw-text-[12px]  max-sm:tw-text-center max-sm:tw-my-2">Here's a summary of your order statistics over the past 12 months:</p>
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
  </div>
</x-layout.portal.portalDashboardTemplate>