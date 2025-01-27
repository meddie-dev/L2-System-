<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">

      <h6 class="tw-ml-4 tw-mb-4 tw-text-gray-500">Logistics 2</h6>
      <div class="nav | max-sm:tw-text-sm ">
        <hr>
        <!-- Heading -->
        <div class="sb-sidenav-menu-heading">Dashboard</div>

        <!-- Dashboard -->
        <a class="nav-link collapsed"
          href="{{ (Auth::user()->hasRole('Super Admin')) ? '/superadmin/dashboard' : ((Auth::user()->hasRole('Admin')) ? '/admin/dashboard' : '/staff/dashboard') }}">
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
              <a class="nav-link" href="/admin/vendor/approval">Vendor Approval</a>
              <a class="nav-link" href="/admin/vendor/order-review">Order Review</a>
              <a class="nav-link" href="/admin/vendor/profiles">Vendor Profiles</a>
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
              <a class="nav-link" href="/admin/audit/reporting">Reporting</a>
              <a class="nav-link" href="/admin/audit/trails">Trails and Logs </a>
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
              <a class="nav-link" href="/admin/vehicle/scheduling">Reservation Scheduling</a>
              <a class="nav-link" href="/admin/vehicle/history">Reservation History and Logs</a>
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
              <a class="nav-link" href="/admin/document/storage">Document Storage </a>
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
              <a class="nav-link" href="/admin/fleet/inventory">Vehicle Inventory</a>
              <a class="nav-link" href="/admin/fleet/maintenance">Maintenance Management</a>
            </nav>
          </div>

          <!-- Fraud Detection -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFraud" aria-expanded="false" aria-controls="collapseFraud">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-user-secret"></i></div>
            Fraud Detection
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseFraud" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/admin/fraud/detection">Fraud Detection Form</a>
            </nav>
          </div>
        </div>
        @endif

        @if(Auth::user()->hasRole('admin'))
        <!-- Supplier-specific links -->
        <div>
          <!-- Document Management -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDocument" aria-expanded="false" aria-controls="collapseDocument">
            <div class="sb-nav-link-icon"><i class="fa-regular fa-file"></i></div>
            Document Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseDocument" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/supplier/document/upload">Upload Documents</a>
              <a class="nav-link" href="/supplier/document/view_document">View Documents</a>
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
              <a class="nav-link" href="/supplier/vendor/registration">Registration</a>
              <a class="nav-link" href="/supplier/vendor/vendors">View Vendors</a>
              <a class="nav-link" href="/supplier/vendor/profile">Manage Profile</a>
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
              <a class="nav-link" href="/supplier/vehicle/request">Reservations Request</a>
              <a class="nav-link" href="/supplier/vehicle/status">Reservation Status</a>
              <a class="nav-link" href="/supplier/vehicle/history">Reservation History</a>
            </nav>
          </div>
        </div>
        @endif

        @if(Auth::user()->hasRole('staff'))
        <!-- Staff-specific links -->
        <div>
          <!-- Vehicle Reservation -->
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVehicle" aria-expanded="false" aria-controls="collapseVehicle">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-van-shuttle"></i></div>
            Vehicle Reservation
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseVehicle" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="/dashboard/vehicle/request">Request Reservations</a>
              <a class="nav-link" href="/dashboard/vehicle/status">View Status</a>
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
              <a class="nav-link" href="/dashboard/document/upload">Upload Documents</a>
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
              <a class="nav-link" href="/dashboard/vendor/contracts">View Approved Vendors</a>
            </nav>
          </div>
        </div>
        @endif

        <!-- Addons or Features -->
        <div class="sb-sidenav-menu-heading">Addons</div>
        <a class="nav-link" href="#">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-map"></i></div>
          Map
        </a>
        <a class="nav-link" href="#">
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