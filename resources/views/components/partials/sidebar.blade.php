<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">

      <h6 class="tw-ml-4 tw-mb-4 tw-text-gray-500">Logistics 2</h6>
      <div class="nav">
        <hr>
        <!-- Heading -->
        <div class="sb-sidenav-menu-heading">Dashboard</div>

        <!-- Dashboard -->
        <a class="nav-link collapsed"
          href="">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
          Dashboard
        </a>

        <!-- Heading -->
        <div class="sb-sidenav-menu-heading">Services</div>

        @if(Auth::user()->role === 'admin')
        <!-- Admin-specific links -->

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
        @endif

        @if(Auth::user()->role === 'supplier')
        <!-- Supplier-specific links -->
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
        @endif

        @if(Auth::user()->role === 'distributor')
        <!-- Distributor-specific links -->

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
        @endif

        <div class="sb-sidenav-menu-heading">Addons</div>
        <a class="nav-link" href="#">
          <div class="sb-nav-link-icon"><i class="fa-regular fa-building"></i></i></div>
          <!-- DS Global Holdings Inc. --> ( Client Website )
        </a>
      </div>
    </div>
    <div class="sb-sidenav-footer">
      <div class="small">Logged in as:</div>
      @auth
      {{ Auth::user()->firstName }} {{ Auth::user()->lastName }} ({{ Auth::user()->hasRole('Super Admin') ? 'Super Admin' : (Auth::user()->hasRole('Admin') ? 'Admin' : 'Staff') }})
      @endauth
    </div>
  </nav>
</div>