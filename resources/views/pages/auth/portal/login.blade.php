<x-layout.portal.portalAuthTemplate>
  <div class="container tw-mt-16 tw-max-w-[85%] | max-sm:tw-mt-5 ">
    <div class="row justify-content-center ">
      <div class="tw-h-[82vh] tw-flex | max-sm:tw-h-full">
        <div class="tw-bg-blue-500 tw-w-full card shadow-lg border-0 rounded-lg tw-rounded-r-none my-5 tw-overflow-hidden | max-sm:tw-hidden  ">
          <div class="tw-relative tw-z-10  tw-flex tw-flex-col tw-justify-center tw-items-center tw-h-full">
            <img
              src="/img/logo.png"
              alt="Logo"
              style="max-width: 250px; max-height: 50px; filter: brightness(0) invert(1); " />
            <h2 class="tw-text-2xl tw-py-3 tw-text-white tw-font-extralight">Vendor Portal</h2>
          </div>
          <div class="tw-absolute tw-bottom-[-3rem]  tw-left-[-10rem] tw-rotate-[-35deg] tw-opacity-30 ">
            <img src="/img/logo_white.png" class="tw-w-[500px] tw-object-cover tw-h-[250px] tw-object-top " >
          </div>
        </div>
        <div class="card shadow-lg border-0 rounded-lg my-5 tw-min-w-[43%] tw-rounded-l-none | max-sm:tw-rounded-lg max-sm:tw-text-sm max-sm:tw-min-w-full">
          <div class="card-header  ">
            <h3 class="text-center tw-font-extralight tw-mt-4 tw-text-xl | max-sm:tw-mt-2 max-sm:tw-text-lg ">Vendor Portal</h3>
            <p class="text-center tw-text-sm text-body-tertiary">Welcome to the Logistics II Vendor Portal!</p>
          </div>
          <div class="card-body tw-text-sm ">
            <form method="POST" action="/portal/login">
              @csrf
              <div class="form-floating my-3">
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
                <a href="{{route('password.request')}}" class="tw-text-sm | tw-text-blue-700">Forgot Password?</a>
                <button type="submit" class="btn btn-primary tw-px-6">Login</button>
              </div>
            </form>
          </div>
          <div class="card-footer text-center py-3">
            @if(!Auth::check() || !Auth::user()->hasRole('Vendor'))
            <div class="tw-text-sm | tw-text-blue-700"><a href="/portal/register">Don't have an account yet? Create one now!</a></div>
            @else
            <a class=" tw-text-blue-700" href="{{route('vendorPortal.dashboard')}}">Go back to Dashboard</a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="tw-absolute tw-top-10 tw-left-5 tw-flex tw-justify-start | max-sm:tw-top-3">
    @if(!Auth::check() || !Auth::user()->hasRole('Vendor'))
    <a href="/login" class="tw-flex tw-items-center tw-justify-center tw-py-2 tw-px-4 tw-rounded-full tw-bg-white tw-text-blue-500 tw-shadow-xl hover:tw-bg-blue-500 hover:tw-text-white tw-transition tw-duration-300 tw-ease-in-out">
      <i class="fa-solid fa-arrow-left-long fa-fw tw-mr-2"></i> Switch to Logistics Login
    </a>
    @endif
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
  </x-layout.portal.portalAuthTemplateauthTemplate>