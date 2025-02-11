<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark {{ auth()->user()->hasRole('Vendor') ? 'sb-sidenav-light' : 'sb-sidenav-dark' }}" id="sidenavAccordion">
    <div class="sb-sidenav-menu">

      <h6 class="tw-ml-4 tw-mb-4 tw-text-gray-500">Logistics 2</h6>
      <div class="nav | max-sm:tw-text-sm ">
        <hr>
        <!-- Heading -->
        <div class="sb-sidenav-menu-heading">Dashboard</div>

        <!-- Dashboard -->
        <a class="nav-link collapsed"
          href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : (Auth::user()->hasRole('Staff') ? 'staff.dashboard' : (Auth::user()->hasRole('Vendor') ? 'vendorPortal.dashboard' : 'driver.dashboard')))) }}">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
          Dashboard
        </a>

        <!-- Heading -->
        <div class="sb-sidenav-menu-heading">Modules</div>

        @if(Auth::user()->hasRole('Super Admin'))
        <!-- Super Admin-specific links -->
        <div>
          <!-- Vendor Management -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVendor" aria-expanded="false" aria-controls="collapseVendor">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
            Vendor Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseVendor" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/superadmin/vendor/approval">Vendor Approval</a>
              <a class="nav-link" href="/superadmin/vendor/profiles">Vendor Profiles</a>
            </nav>
          </div>

          <!-- Audit Management -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAudit" aria-expanded="false" aria-controls="collapseAudit">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-file-circle-check"></i></div>
            Audit Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseAudit" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/superadmin/audit/final-report">Final Report</a>
              <a class="nav-link" href="/superadmin/audit/users">Manage Users </a>
            </nav>
          </div>

          <!-- Vehicle Reservation -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVehicle" aria-expanded="false" aria-controls="collapseVehicle">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-van-shuttle"></i></div>
            Vehicle Reservation
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseVehicle" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/superadmin/vehicle/reservation">Manage Reservations</a>
            </nav>
          </div>

          <!-- Document Management -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDocument" aria-expanded="false" aria-controls="collapseDocument">
            <div class="sb-nav-link-icon"><i class="fa-regular fa-file"></i></div>
            Document Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseDocument" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/superadmin/document/storage">Document Storage</a>
              <a class="nav-link" href="/superadmin/document/archived">Archived Documents</a>
            </nav>
          </div>

          <!-- Fleet Management -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFleet" aria-expanded="false" aria-controls="collapseFleet">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-gauge-high"></i></div>
            Fleet Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseFleet" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/superadmin/fleet/driver">Driver Management</a>
              <a class="nav-link" href="/superadmin/fleet/fuel">Fuel Management</a>
              <a class="nav-link" href="/superadmin/fleet/maintenance">Maintenance Management</a>
            </nav>
          </div>
        </div>
        @endif

        @if(Auth::user()->hasRole('Admin'))
        <div>
          <!-- Vendor Management -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVendor" aria-expanded="false" aria-controls="collapseVendor">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
            Vendor Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseVendor" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/admin/vendor/vendor-request">Vendor Request</a>
              <a class="nav-link" href="/admin/vendor/vendor-approved">Approved Request</a>
            </nav>
          </div>

          <!-- Audit Management -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAudit" aria-expanded="false" aria-controls="collapseAudit">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-file-circle-check"></i></div>
            Audit Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseAudit" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/admin/audit/generate-report">Generate Report</a>
              <a class="nav-link" href="/admin/audit/risk-assessment">Risk Assessment</a>
              <a class="nav-link" href="/admin/audit/review-staff">Review Staff Activities</a>
            </nav>
          </div>

          <!-- Vehicle Reservation -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVehicle" aria-expanded="false" aria-controls="collapseVehicle">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-van-shuttle"></i></div>
            Vehicle Reservation
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseVehicle" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/admin/vehicle/reservation">Manage Reservations</a>
              <a class="nav-link" href="/admin/vehicle/review-request">Review Reservation Requests</a>
            </nav>
          </div>

          <!-- Document Management -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDocument" aria-expanded="false" aria-controls="collapseDocument">
            <div class="sb-nav-link-icon"><i class="fa-regular fa-file"></i></div>
            Document Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseDocument" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/admin/document/storage">Document Storage</a>
              <a class="nav-link" href="/admin/document/archived">Review Document Submissions</a>
            </nav>
          </div>

          <!-- Fleet Management -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFleet" aria-expanded="false" aria-controls="collapseFleet">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-gauge-high"></i></div>
            Fleet Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseFleet" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseInventory" aria-expanded="false" aria-controls="collapseInventory">
                Driver Management
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
              </a>
              <div class="collapse" id="collapseInventory" aria-labelledby="headingOne" data-bs-parent="#collapseFleet">
                <nav class="sb-sidenav-menu-nested nav">
                  <a class="nav-link" href="/admin/fleet/driver/request">Approve Driver Requests</a>
                  <a class="nav-link" href="/admin/fleet/driver/performance">Monitor Driver Performance</a>
                </nav>
              </div>
              <a class="nav-link" href="/admin/fleet/fuel">Fuel Management</a>
              <a class="nav-link" href="/admin/fleet/maintenance">Maintenance Management</a>
            </nav>
          </div>
        </div>
        @endif

        @if(Auth::user()->hasRole('Staff'))
        <!-- Staff-specific links -->
        <div>
          <!-- Audit Managements -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAudit" aria-expanded="false" aria-controls="collapseAudit">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-file-circle-check"></i></div>
            Audit Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseAudit" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/staff/dashboard/audit/risk">Risk Assessment Report</a>
            </nav>
          </div>

          <!-- Vehicle Reservation -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVehicle" aria-expanded="false" aria-controls="collapseVehicle">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-van-shuttle"></i></div>
            Vehicle Reservation
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseVehicle" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/staff/dashboard/vehicle/request">Request Reservations</a>
              <a class="nav-link" href="/staff/dashboard/vehicle/status">Reservation Status</a>
            </nav>
          </div>

          <!-- Document Management -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDocument" aria-expanded="false" aria-controls="collapseDocument">
            <div class="sb-nav-link-icon"><i class="fa-regular fa-file"></i></div>
            Document Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseDocument" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/staff/dashboard/document/request">View Request Documents</a>
              <a class="nav-link" href="/staff/dashboard/document/approved">View Approved Documents</a>
            </nav>
          </div>

          <!-- Vendor Management -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVendor" aria-expanded="false" aria-controls="collapseVendor">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
            Vendor Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseVendor" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/staff/dashboard/vendor/request">Manage Request</a>
            </nav>
          </div>

          <!-- Fleet Management -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFleet" aria-expanded="false" aria-controls="collapseFleet">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-truck"></i></div>
            Fleet Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseFleet" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/staff/dashboard/fleet/issue">Report Vehicle Issues</a>
              <a class="nav-link" href="/staff/dashboard/fleet/request">Request Vehicle Maintenance</a>
            </nav>
          </div>
        </div>
        @endif

        @if(Auth::user()->hasRole('Vendor'))
        <!-- Vendor-specific links -->
        <div>
          <!-- Order Managements -->
          <a class="nav-link collapsed" href="/portal/vendor/dashboard/order">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-box"></i></div>
            Order Management
          </a>

          <!-- Document Management -->
          <a class="nav-link collapsed" href="/portal/vendor/dashboard/document">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-folder"></i></div>
            Uploaded Document
          </a>

           <!-- Payment Management -->
           <a class="nav-link collapsed" href="/portal/vendor/dashboard/payment">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-credit-card"></i></div>
            Payment
          </a>

          <!-- Transaction History Management -->
          <a class="nav-link collapsed" href="/portal/vendor/dashboard/transaction">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-history"></i></div>
            Transaction History
          </a>
        </div>
        @endif

        @if(Auth::user()->hasRole('Driver'))
        <!-- Driver-specific links -->
        <div>
          <!-- Vehicle Reservation -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVehicle" aria-expanded="false" aria-controls="collapseVehicle">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-van-shuttle"></i></div>
            Vehicle Reservation
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseVehicle" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/driver/dashboard/vehicle/inbox">Reservations Inbox</a>
              <a class="nav-link" href="/driver/dashboard/vehicle/logs">Reservation Logs</a>
            </nav>
          </div>

          <!-- Document Management -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDocument" aria-expanded="false" aria-controls="collapseDocument">
            <div class="sb-nav-link-icon"><i class="fa-regular fa-file"></i></div>
            Document Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseDocument" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/driver/dashboard/document/request">View Request Documents</a>
              <a class="nav-link" href="/driver/dashboard/document/approved">View Approved Documents</a>
            </nav>
          </div>

          <!-- Fleet Management -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFleet" aria-expanded="false" aria-controls="collapseFleet">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-truck"></i></div>
            Fleet Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseFleet" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/driver/dashboard/fleet/issue">Fuel Management</a>
              <a class="nav-link" href="/driver/dashboard/fleet/issue">Report Vehicle Issues</a>
              <a class="nav-link" href="/driver/dashboard/fleet/request">Request Vehicle Maintenance</a>
            </nav>
          </div>
        </div>
        @endif

        <!-- Addons or Features -->
        <div class="sb-sidenav-menu-heading">Addons</div>
        <a class="nav-link" href="{{ route('map') }}">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-map"></i></div>
          Map
        </a>
        <a class="nav-link" href="{{ route('calendar') }}">
          <div class="sb-nav-link-icon"><i class="fa-regular fa-calendar"></i></div>
          Calendar and Schedule
        </a>

        <!-- Settings in Small Screen -->
        <div class="tw-hidden max-sm:tw-block">
          <div class="sb-sidenav-menu-heading">Tools</div>
          <a class="nav-link" href="{{ route('settings.index') }}">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>
            Settings
          </a>
          <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-right-from-bracket"></i></div>
            Logout
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </div>
    </div>
    <div class="sb-sidenav-footer | max-sm:tw-text-sm">
      <div class="small">Logged in as:</div>
      @auth
      <div style="font-size: {{ Str::length(Auth::user()->firstName . ' ' . Auth::user()->lastName) > 8 ? 'small' : 'inherit' }}">
        {{ Auth::user()->firstName }} {{ Auth::user()->lastName }} ({{ Auth::user()->roles->pluck('name')->first() }})
      </div>
      @endauth
    </div>
  </nav>
</div>