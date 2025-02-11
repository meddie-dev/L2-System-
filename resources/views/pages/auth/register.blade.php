<x-layout.authTemplate>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-7">
        <div class="card shadow-lg border-0 rounded-lg mt-5 mb-5">
          <div class="card-header">
            <h3 class="text-center font-weight-light tw-mt-4 tw-text-2xl | max-sm:tw-mt-2 max-sm:tw-text-xl max-sm:tw-font-extralight">Create Account</h3>
            <p class="text-center text-body-tertiary | max-sm:tw-text-sm">Register now to take your logistics to the next level!</p>
          </div>
          <div class="card-body | max-sm:tw-text-sm">
            <form method="POST" action="/register">
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

              @php
              $superAdminExists = \App\Models\User::role('Super Admin')->exists();
              $adminExists = \App\Models\User::role('Admin')->exists();
              $staffCount = \App\Models\User::role('Staff')->count();
              @endphp

              <div class="form-floating mb-3">
                <select class="form-select p-3" id="role" name="role" required>
                  <option value="" selected>Select Role</option>

                  <!-- Show "Super Admin" option if no Super Admin exists -->
                  @if(!$superAdminExists)
                  <option value="Super Admin">Super Admin</option>
                  @endif

                  <!-- Show "Admin" option if no Admin exists -->
                  @if(!$adminExists)
                  <option value="Admin">Admin</option>
                  @endif

                  <!-- Show "Staff" option if fewer than 5 Staff exist -->
                  @if($staffCount < 5)
                    <option value="Staff">Staff</option>
                    @endif

                    <!-- Always show "Driver" option -->
                    <option value="Driver">Driver</option>
                </select>
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
                  <button type="submit" class="btn btn-primary btn-block | max-sm:tw-text-sm">Create Account</button>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer text-center py-3">
            <div class="small tw-text-blue-700 | max-sm:tw-text-sm"><a href="/login">Have an account? Go to login</a></div>
          </div>
        </div>
      </div>
    </div>
    <div class="tw-absolute tw-top-10 tw-right-5 | max-sm:tw-top-5">
      @if(!Auth::check() || !Auth::user()->hasRole('Super Admin, Admin, Staff'))
      <a href="/portal/login" class="tw-flex tw-items-center tw-justify-center tw-py-2 tw-px-4 tw-rounded-full tw-bg-blue-500 tw-text-white hover:tw-bg-white hover:tw-text-blue-500 hover:tw-border hover:tw-border-blue-500 tw-shadow-xl tw-transition tw-duration-300 tw-ease-in-out">
        Switch to Vendor Portal<i class="fa-solid fa-arrow-right-long fa-fw tw-ml-2"></i>
      </a>
      @endif
    </div>
  </div>
</x-layout.authTemplate>