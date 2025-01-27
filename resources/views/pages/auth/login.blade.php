<x-layout.authTemplate>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-lg border-0 rounded-lg mt-5 mb-5">
          <div class="card-header">
            <h3 class="text-center font-weight-light mt-4 | tw-text-2xl ">Access Account</h3>
            <p class="text-center text-body-tertiary">Join us again for seamless tracking!</p>
          </div>
          <div class="card-body">
            <form method="POST" action="/login">
              @csrf
              <div class="form-floating mb-3">
                <input
                  class="form-control @error('email') is-invalid @enderror"
                  id="inputEmail"
                  type="email"
                  name="email"
                  placeholder="name@example.com"
                  value="{{ old('email') }}"
                  required
                  autocomplete="email"
                  autofocus />
                <label for="inputEmail">Email address</label>
                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="mb-3 position-relative">
                <div class="form-floating">
                  <input
                    class="form-control @error('password') is-invalid @enderror"
                    id="inputPassword"
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                    autocomplete="current-password" />
                  <label for="inputPassword">Password</label>
                  <span
                    class="position-absolute top-50 end-0 translate-middle-y me-3"
                    style="cursor: pointer;"
                    id="togglePasswordIcon">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                  </span>
                </div>
                @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="form-check mb-3">
                <input
                  class="form-check-input"
                  id="inputRememberPassword"
                  type="checkbox"
                  name="remember"
                  value="1"
                  {{ old('remember') ? 'checked' : '' }} />
                <label class="form-check-label" for="inputRememberPassword">Keep me signed in</label>
              </div>
              <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                <a href="{{route('password.request')}}" class="small | tw-text-blue-700">Forgot Password?</a>
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
            </form>
          </div>
          <div class="card-footer text-center py-3">
            @if(!Auth::check())
            <div class="small | tw-text-blue-700"><a href="/register">Don't have an account yet? Create one now!</a></div>
            @else
            <a href="@if(Auth::user()->hasRole('Super Admin')) /superadmin/dashboard @elseif(Auth::user()->hasRole('Admin')) /admin/dashboard @elseif(Auth::user()->hasRole('Staff')) /staff/dashboard @endif" class="text-primary">Go back to Dashboard</a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Password Show/Hide
    const togglePasswordIcon = document.querySelector("#togglePasswordIcon");
    const passwordInput = document.querySelector("#inputPassword");
    const eyeIcon = document.querySelector("#eyeIcon");

    togglePasswordIcon.addEventListener("click", function() {
      // Toggle the password input type
      const isPassword = passwordInput.type === "password";
      passwordInput.type = isPassword ? "text" : "password";

      // Change the icon
      eyeIcon.classList.toggle("bi-eye");
      eyeIcon.classList.toggle("bi-eye-slash");
    });
  </script>
</x-layout.authTemplate>