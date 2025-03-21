<x-layout.authTemplate>
  <div style="margin-top: 20vh" class="d-flex h-100 text-center align-items-center justify-content-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mx-auto">
          <div class="mb-2">
            <h1 class="display-1">404</h1>
          </div>
          <p class="lead tw-mb-4">This requested URL was not found on this server.</p>
          <a href="
            @if(Auth::user()->hasRole('Super Admin')) 
                /superadmin/dashboard 
            @elseif(Auth::user()->hasRole('Admin')) 
                /admin/dashboard 
            @elseif(Auth::user()->hasRole('Staff')) 
                /staff/dashboard 
            @endif" class="btn btn-primary">
            <i class="fas fa-arrow-left me-1"></i>
            Return to Dashboard
          </a>
        </div>
      </div>
    </div>
  </div>
</x-layout.authTemplate>

