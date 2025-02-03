<x-layout.authTemplate>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
          <div class="card-header">
            <h3 class="text-center font-weight-light mt-4 mb-2 | tw-text-2xl ">Account Deletion</h3>
          </div>
          <div class="card-body">
            <div class="small mb-3 text-muted">This action is permanent. You will be unable to recover your account.</div>
            <form method="POST" action="{{ route('settings.delete-account') }}">
              @csrf
              <div class="form-floating mb-3">
                <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" placeholder="Enter your password" required />
                <label for="password">Password</label>
                @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="form-floating mb-3">
                <input class="form-control @error('confirmation_text') is-invalid @enderror" id="confirmation_text" type="text" name="confirmation_text" placeholder="Type DELETE MY ACCOUNT" required />
                <label for="confirmation_text" placeholder="Confirmation">Confirmation</label>
                @error('confirmation_text')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="small mb-3 text-muted">Type <code>DELETE MY ACCOUNT</code> in the field below to delete your account. This action is permanent. You will be unable to recover your account.</div>
              <div class="d-flex align-items-center justify-content-end mt-4 mb-0">
                <button class="btn btn-danger" type="submit">Delete Account</button>
              </div>
            </form>
          </div>
          <div class="card-footer text-center py-3">
            @if(!Auth::check())
            <div class="small | tw-text-blue-700"><a href="@if(Auth::check() && Auth::user()->hasRole('Vendor')) {{route('portal.register')}} @else {{route('register')}} @endif">Don't have an account yet? Create one now!</a></div>
            @else
            <a href="@if(Auth::user()->hasRole('Super Admin')) /superadmin/dashboard @elseif(Auth::user()->hasRole('Admin')) /admin/dashboard @elseif(Auth::user()->hasRole('Staff')) /staff/dashboard @elseif(Auth::user()->hasRole('Vendor')) /portal/vendor/dashboard @elseif(Auth::user()->hasRole('Driver')) /driver/dashboard @endif" class="text-primary">Go back to Dashboard</a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</x-layout.authTemplate>
