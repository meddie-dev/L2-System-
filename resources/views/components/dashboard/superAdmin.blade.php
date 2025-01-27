<x-layout.dashboardTemplate>
  <div class="container-fluid tw-my-5 | tw-px-4 max-sm:tw-px-0">
    <nav class="tw-flex max-sm:flex-start" aria-label="Breadcrumb">
      <ol class="tw-inline-flex tw-items-center tw-space-x-1 md:tw-space-x-2 rtl:tw-space-x-reverse">
        <x-partials.breadcrumb href="{{ (Auth::user()->hasRole('Super Admin')) ? '/superadmin/dashboard' : ((Auth::user()->hasRole('Admin')) ? '/admin/dashboard' : '/staff/dashboard') }}" :active="true" :isLast="true">
          <div class="sb-nav-link-icon tw-pr-2 max-sm:tw-text-sm"><i class="fa-solid fa-table-columns max-sm:tw-text-sm"></i>
            Dashboard
          </div>
        </x-partials.breadcrumb>
      </ol>
    </nav>

    <div class="tw-max-w-9xl tw-mx-auto tw-mt-6 tw-mb-10 tw-bg-white tw-rounded-lg tw-shadow-lg tw-p-8 | max-sm:tw-p-4 max-sm:tw-my-6"
      data-aos="fade">
      <div>
        <h3 class="tw-text-xl tw-font-semibold tw-text-gray-700 tw-mb-4 | max-sm:tw-mb-2 max-sm:tw-text-[16px]">Hello, {{ Auth::user()->firstName }}!</h3>
        <p class="tw-text-sm tw-text-gray-500 tw-indent-14 max-sm:tw-text-[12px] max-sm:tw-text-justify max-sm:tw-indent-5">
          Welcome to the Super Admin Dashboard! From here, you can manage vendor approvals, review orders, access vendor profiles, monitor audit trails, manage fleet maintenance, oversee vehicle reservations, track documents, and more. If you have any questions or need any assistance, please don't hesitate to reach out to us. We're here to help!
        </p>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-6" data-aos="fade">
        <div class="card mb-4">
          <div class="card-header | max-sm:tw-text-sm">
            <i class="fas fa-chart-area me-1"></i>
            <b> Annual User Count Overview: </b> A 12-Month Analysis
          </div>
          <div class="chart-container">
            <canvas id="userChart" width="400" height="200"></canvas>
            <script>
              const months = @json($months);
              const userCounts = @json($userCounts);
              const ctx = document.getElementById('userChart');
              if (ctx) {
                const userChart = new Chart(ctx.getContext('2d'), {
                  type: 'line',
                  data: {
                    labels: months,
                    datasets: [{
                      label: 'Users',
                      data: userCounts,
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
                        position: 'top',
                      },
                    },
                  }
                });
              } else {
                console.error("Canvas element not found!");
              }
            </script>
          </div>
        </div>
      </div>
      <div class="col-xl-6" data-aos="fade">
        <div class="card mb-4">
          <div class="card-header | max-sm:tw-text-sm">
            <i class="fas fa-chart-area me-1"></i>
            <b> User Statistics:</b> Identifying Counts by Role
          </div>
          <div class="chart-container">
            <canvas id="userRoleChart" width="400" height="200"></canvas>
            <script>
              const roles = @json($roles);
              const roleCounts = @json($roleCounts);
              const rtx = document.getElementById('userRoleChart').getContext('2d');
              const userRoleChart = new Chart(rtx, {
                type: 'doughnut',
                data: {
                  labels: roles,
                  datasets: [{
                    label: 'Users',
                    data: roleCounts,
                    backgroundColor: [
                      'rgba(255, 99, 132, 0.2)',
                      'rgba(54, 162, 235, 0.2)',
                      'rgba(255, 206, 86, 0.2)'
                    ],
                    borderColor: [
                      'rgba(255, 99, 132, 1)',
                      'rgba(54, 162, 235, 1)',
                      'rgba(255, 206, 86, 1)'
                    ],
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
            </script>
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-4" data-aos="fade-up">
      <div class="card-header | max-sm:tw-text-sm">
        <i class="fas fa-table me-1"></i>
        Users List
      </div>
      <div class="card-body | max-sm:tw-text-sm">
        <table id="datatablesSimple">
          <thead>
            <tr>
              <th>Name</th>
              <th>Position</th>
              <th>Email</th>
              <th>Create at</th>
              <th>Update at</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Name</th>
              <th>Position</th>
              <th>Email</th>
              <th>Create at</th>
              <th>Update at</th>
            </tr>
          </tfoot>
          <tbody>
            @foreach($users as $user)
            <tr>
              <td>{{ $user->firstName }} {{ $user->lastName }}</td>
              <td>
                @if ($user->hasRole('Super Admin'))
                <span class="badge rounded-pill bg-danger">Super Admin</span>
                @elseif ($user->hasRole('Admin'))
                <span class="badge rounded-pill bg-warning text-dark">Admin</span>
                @else
                <span class="badge rounded-pill bg-primary">Staff</span>
                @endif
              </td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->created_at->format('Y/m/d') }}</td>
              <td>{{ $user->updated_at->format('Y/m/d') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
  </div>
</x-layout.dashboardTemplate>