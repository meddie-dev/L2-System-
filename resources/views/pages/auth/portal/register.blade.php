<x-layout.portal.portalAuthTemplate>
  <div class="container tw-mt-16 tw-max-w-[85%] | max-sm:tw-mt-5 ">
    <div class="row justify-content-center">
      <div class="tw-h-[87vh] tw-flex | max-sm:tw-h-full">
        <div class="tw-bg-blue-500 tw-w-full card shadow-lg border-0 rounded-lg tw-rounded-r-none my-5 tw-overflow-hidden | max-sm:tw-hidden ">
          <div class="tw-relative tw-z-10 tw-flex tw-flex-col tw-justify-center tw-items-center tw-h-full">
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
        <div class="card shadow-lg border-0 rounded-lg mt-5 mb-5 tw-min-w-[43%] tw-rounded-l-none | max-sm:tw-rounded-lg max-sm:tw-text-sm max-sm:tw-min-w-full">
          <div class="card-header">
            <h3 class="text-center tw-font-extralight tw-mt-4 tw-text-xl | max-sm:tw-mt-2 max-sm:tw-text-lg">Vendor Portal Registration</h3>
            <p class="text-center text-body-tertiary ">Sign up for the Logistics II (Vendor Portal).</p>
          </div>
          <div class="card-body">
            <form method="POST" action="/portal/register">
              @csrf
              <div class="row mb-3">
                <div class="col-md-6">
                  <div class="form-floating mb-3 mb-md-0">
                    <input
                      class="form-control @error('first_name') is-invalid @enderror"
                      id="inputFirstName"
                      type="text"
                      name="first_name"
                      placeholder="Enter your first name"
                      value="{{ old('first_name') }}"
                      required
                      autofocus />
                    <label for="inputFirstName">First name</label>

                    @error('first_name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input
                      class="form-control @error('last_name') is-invalid @enderror"
                      id="inputLastName"
                      type="text"
                      name="last_name"
                      placeholder="Enter your last name"
                      value="{{ old('last_name') }}"
                      required />
                    <label for="inputLastName">Last name</label>

                    @error('last_name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                </div>
              </div>

              <div class="form-floating mb-3">
                <input
                  class="form-control @error('email') is-invalid @enderror"
                  id="inputEmail"
                  type="email"
                  name="email"
                  placeholder="name@example.com"
                  value="{{ old('email') }}"
                  required />
                <label for="inputEmail">Email address</label>

                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <div class="form-floating mb-3 mb-md-0">
                    <input
                      class="form-control @error('password') is-invalid @enderror"
                      id="inputPassword"
                      type="password"
                      name="password"
                      placeholder="Create a password"
                      required />
                    <label for="inputPassword">Password</label>

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3 mb-md-0">
                    <input
                      class="form-control"
                      id="inputPasswordConfirm"
                      type="password"
                      name="password_confirmation"
                      placeholder="Confirm password"
                      required />
                    <label for="inputPasswordConfirm">Confirm Password</label>
                  </div>
                </div>
              </div>

              <div class="mt-4 mb-0">
                <div class="d-grid">
                  <button type="submit" class="btn btn-primary btn-block">Create Account</button>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer text-center py-3">
            <div class="small | tw-text-blue-700"><a href="/portal/login">Have an account? Go to login</a></div>
          </div>
        </div>
      </div>
    </div>

    <div class="tw-absolute tw-top-10 tw-left-5 tw-flex tw-justify-start | max-sm:tw-top-3">
    @if(!Auth::check() || !Auth::user()->hasRole('Vendor'))
    <a href="/login" class="tw-flex tw-items-center tw-justify-center tw-py-2 tw-px-4 tw-rounded-full tw-shadow-xl tw-bg-white tw-text-blue-500 hover:tw-bg-blue-500 hover:tw-text-white tw-transition tw-duration-300 tw-ease-in-out">
      <i class="fa-solid fa-arrow-left-long fa-fw tw-mr-2"></i> Switch to Logistics Login
    </a>
    @endif
  </div>
  </div>
</x-layout.portal.portalAuthTemplate>