<x-layout.authTemplate>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
          <div class="card-header">
            <h3 class="text-center font-weight-light mt-4 mb-2 | tw-text-2xl ">Password Recovery</h3>
          </div>
          <div class="card-body">
            <div class="small mb-3 text-muted">Enter your email address and we will send you a link to reset your password.</div>
            <form method="POST" action="{{ route('password.email') }}">
              @csrf
              <div class="form-floating mb-3">
                <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" placeholder="name@example.com" value="{{ old('email') }}" />
                <label for="email">Email address</label>
                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                <a class="small text-primary" href="{{route('login')}}">Return to login</a>
                <button class="btn btn-primary" type="submit">Send Password Reset Link</button>
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