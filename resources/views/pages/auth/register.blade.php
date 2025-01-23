<x-layout.authTemplate>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-7">
        <div class="card shadow-lg border-0 rounded-lg mt-5 mb-5">
          <div class="card-header">
            <h3 class="text-center font-weight-light mt-4 | tw-text-2xl">Create Account</h3>
            <p class="text-center text-body-tertiary">Register now to take your logistics to the next level!</p>
          </div>
          <div class="card-body">
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
            <div class="small | tw-text-blue-700"><a href="/login">Have an account? Go to login</a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-layout.authTemplate>