<x-layout.authTemplate>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
          <div class="card-header">
            <h3 class="text-center font-weight-light mt-4 | tw-text-2xl ">Reset Password</h3>
            <p class="text-center mb-2  text-body-tertiary">Fill up the form and add the new password.</p>
          </div>
          <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}">
              @csrf
              <input type="hidden" name="token" value="{{ $request->route('token') }}">
              <div class="form-floating mb-3">
                <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                <label for="email">Email address</label>
                @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="form-floating mb-3">
                <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" placeholder="New Password" required>
                <label for="password">New Password</label>
                @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              <div class="form-floating mb-3">
                <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm Password" required>
                <label for="password_confirmation">Confirm Password</label>
              </div>
              <p class="small mb-2 text-body-tertiary"><span class="fw-bold">Password strength:</span> <br>
              Use at least 8 characters. Don’t use a password from another site, or something too obvious like your pet’s name. </p>
              <button type="submit" class="btn btn-primary float-end">Change password</button>
            </form>
          </div>
          <div class="card-footer text-center py-3">
            @if (Auth::check())
            <a href="@if(Auth::user()->hasRole('Super Admin')) /superadmin/dashboard @elseif(Auth::user()->hasRole('Admin')) /admin/dashboard @elseif(Auth::user()->hasRole('Staff')) /staff/dashboard @elseif(Auth::user()->hasRole('Vendor')) /portal/vendor/dashboard @elseif(Auth::user()->hasRole('Driver')) /driver/dashboard @endif" class="text-primary">Go back to Dashboard</a>
            @else
            <div class="small text-primary"><a href="@if(Auth::check() && Auth::user()->hasRole('Vendor')) {{route('portal.register')}} @else {{route('register')}} @endif">Need an account? Sign up!</a></div>
            @endif
        </div>
      </div>
    </div>
  </div>
</x-layout.authTemplate>