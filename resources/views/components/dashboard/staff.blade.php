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

    <div class="tw-max-w-9xl tw-mx-auto tw-mb-10 tw-bg-white tw-rounded-lg tw-shadow-lg tw-p-8 | max-sm:tw-p-4 max-sm:tw-my-6"
      data-aos="fade">
      <div>
        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-600 tw-mb-4  | max-sm:tw-mb-2 max-sm:tw-text-[16px]">Announcement:</h3>
        <p class="tw-text-sm tw-text-gray-500 tw-indent-14 max-sm:tw-text-[12px] max-sm:tw-text-justify max-sm:tw-indent-5">
          Welcome, {{ Auth::user()->firstName }}!, We're glad you're here! We're excited to announce that we've released a new feature to help you manage your orders more efficiently! You can now view your order statistics over the past 12 months and get insights on how to improve your business. Check it out and let us know what you think!
        </p>
      </div>
    </div>

    <div class="row tw-text-center | max-sm:tw-hidden " data-aos="fade">
      <div class="col-xl-4 col-md-6">
        <div class="card tw-bg-[#212529] text-white mb-4">
          <div class="card-body">
            <p class="tw-text-sm tw-font-semibold">Total Assigned Pending: {{ $pendingCounts }}</p>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-md-6">
        <div class="card tw-bg-[#212529] text-white mb-4">
          <div class="card-body">
            <p class="tw-text-sm tw-font-semibold">Total Assigned Reviewed: {{ $reviewedCounts }}</p>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-md-6">
        <div class="card tw-bg-[#212529] text-white mb-4">
          <div class="card-body">
            <p class="tw-text-sm tw-font-semibold">Total Assigned Completed: {{ $rejectedCounts }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="tw-bg-white tw-rounded-lg tw-mb-4 tw-shadow-lg tw-p-4 | max-sm:tw-p-2">
      <p class="tw-text-sm tw-text-gray-500 tw-mb-4 tw-font-semibold | max-sm:tw-text-[12px]  max-sm:tw-text-center max-sm:tw-my-2">Here's a summary of your order statistics over the past 12 months:</p>
      <div>
        <canvas id="orderRoleChart" width="500" height="100"></canvas>
        <script>
          document.addEventListener("DOMContentLoaded", function() {
            const months = @json($months);
            const reviewCounts = @json($reviewCounts);

            const ctx = document.getElementById('orderRoleChart');
            if (ctx) {
              const datasets = [{
                  label: 'Orders Reviewed',
                  data: reviewCounts.map(item => item.orders),
                  borderColor: 'rgba(255, 99, 132, 1)',
                  backgroundColor: 'rgba(255, 99, 132, 0.2)',
                  borderWidth: 2,
                  tension: 0.4
                },
                {
                  label: 'Payments Reviewed',
                  data: reviewCounts.map(item => item.payments),
                  borderColor: 'rgba(54, 162, 235, 1)',
                  backgroundColor: 'rgba(54, 162, 235, 0.2)',
                  borderWidth: 2,
                  tension: 0.4
                },
                {
                  label: 'Documents Reviewed',
                  data: reviewCounts.map(item => item.documents),
                  borderColor: 'rgba(255, 206, 86, 1)',
                  backgroundColor: 'rgba(255, 206, 86, 0.2)',
                  borderWidth: 2,
                  tension: 0.4
                },
                {
                  label: 'Vehicle Reservations',
                  data: reviewCounts.map(item => item.vehicleReservations),
                  borderColor: 'rgba(75, 192, 192, 1)',
                  backgroundColor: 'rgba(75, 192, 192, 0.2)',
                  borderWidth: 2,
                  tension: 0.4
                },
                {
                  label: 'Incident Reports',
                  data: reviewCounts.map(item => item.incidentReports),
                  borderColor: 'rgba(153, 102, 255, 1)',
                  backgroundColor: 'rgba(153, 102, 255, 0.2)',
                  borderWidth: 2,
                  tension: 0.4
                }
              ];

              new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                  labels: months,
                  datasets: datasets
                },
                options: {
                  responsive: true,
                  plugins: {
                    legend: {
                      position: 'top',
                    },
                    title: {
                      display: true,
                      text: 'Monthly Review Trends'
                    }
                  },
                  scales: {
                    y: {
                      beginAtZero: true,
                      ticks: {
                        stepSize: 1
                      }
                    }
                  }
                }
              });
            } else {
              console.error("Canvas element with ID 'orderRoleChart' not found!");
            }
          });
        </script>
      </div>
    </div>

    <!-- Activity Logs -->
    <div class="tw-bg-white tw-rounded-lg tw-shadow-lg tw-p-4 | max-sm:tw-p-2 max-sm:tw-text-sm ">
      <p class="tw-text-sm tw-text-gray-500 tw-mb-4 tw-font-semibold | max-sm:tw-text-[12px]  max-sm:tw-text-center max-sm:tw-my-2">Below is a list of your Activity logs:</p>
      <div class="card-body tw-px-4 tw-max-h-[calc(100vh-23rem)]">
        <div class="tw-overflow-y-auto tw-max-h-[calc(100vh-23rem)] tw-rounded-lg  tw-mb-4">
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
</x-layout.dashboardTemplate>