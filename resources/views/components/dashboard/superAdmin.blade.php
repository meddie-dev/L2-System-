<x-layout.dashboardTemplate>
  <div class="container-fluid tw-my-10 px-4">
    <nav class="tw-flex tw-mb-5 max-sm:justify-center" aria-label="Breadcrumb">
      <ol class="tw-inline-flex tw-items-center tw-space-x-1 md:tw-space-x-2 rtl:tw-space-x-reverse">
        <x-partials.breadcrumb href="/supplier/dashboard" :active="true" :isLast="true">
          <div class="sb-nav-link-icon tw-pr-2"><i class="fa-solid fa-table-columns"></i></div>
          Dashboard
        </x-partials.breadcrumb>
      </ol>
    </nav>

    <div class="tw-max-w-9xl tw-mx-auto tw-my-10 tw-bg-white tw-rounded-lg tw-shadow-lg tw-p-8" data-aos="fade">
      <div>
        <h3 class="tw-text-xl tw-font-semibold tw-text-gray-700 tw-mb-4">Hello, {{ Auth::user()->firstName }}!</h3>
        <p class="tw-text-sm tw-text-gray-500  tw-indent-14 max-sm:tw-line-clamp-3">Welcome to the Admin Dashboard! From here, you can manage vendor approvals, review orders, access vendor profiles, monitor audit trails, manage fleet maintenance, oversee vehicle reservations, track documents, and more. If you have any questions or need any assistance, please don't hesitate to reach out to us. We're here to help!</p>
      </div>
    </div>
    <div class="row" data-aos="fade">
      <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white mb-4">
          <div class="card-body">Primary Card</div>
          <div class="card-footer d-flex align-items-center justify-content-between">
            <a class="small text-white stretched-link" href="#">View Details</a>
            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white mb-4">
          <div class="card-body">Warning Card</div>
          <div class="card-footer d-flex align-items-center justify-content-between">
            <a class="small text-white stretched-link" href="#">View Details</a>
            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white mb-4">
          <div class="card-body">Success Card</div>
          <div class="card-footer d-flex align-items-center justify-content-between">
            <a class="small text-white stretched-link" href="#">View Details</a>
            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card bg-danger text-white mb-4">
          <div class="card-body">Danger Card</div>
          <div class="card-footer d-flex align-items-center justify-content-between">
            <a class="small text-white stretched-link" href="#">View Details</a>
            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-6" data-aos="fade-right">
        <div class="card mb-4">
          <div class="card-header">
            <i class="fas fa-chart-area me-1"></i>
            <b> Annual User Count Overview: </b> A 12-Month Analysis
          </div>
          <div class="chart-container">

          </div>
        </div>
      </div>
      <div class="col-xl-6" data-aos="fade-left">
        <div class="card mb-4">
          <div class="card-header">
            <i class="fas fa-chart-area me-1"></i>
            <b> User Statistics:</b> Identifying Counts by Role
          </div>
          <div class="chart-container">

          </div>
        </div>
      </div>
    </div>
    <div class="card mb-4" data-aos="fade-up">
      <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Users List
      </div>
      <div class="card-body">
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