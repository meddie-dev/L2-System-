<x-layout.authTemplate>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
          <div class="card-header">
            <h3 class="text-center font-weight-light mt-4 mb-2 | tw-text-2xl ">Two-Factor Authentication</h3>
          </div>
          <div class="card-body">
            <div class="small mb-3 text-muted">Enter your two-factor authentication code to proceed.</div>
            <form method="POST" id="verify-form" action="{{ route('two-factor.verify') }}">
              @csrf
              <div class="form-floating mb-3">
                <input class="form-control @error('code') is-invalid @enderror" id="code" type="text" inputmode="numeric" name="code" placeholder="Two-Factor Code" required />
                <label for="code">Two-Factor Code</label>
                @error('code')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                <button class="btn btn-link" type="submit" form="resend-code-form" id="resend-code" disabled style="cursor: not-allowed; color: gray; font-size: smaller;">
                  <span id="resend-code-text">Didn't get the code? </span><span id="countdown">(20s)</span>
                </button>
                <script>
                  (function() {
                    let countdown = 20;
                    const countdownElement = document.getElementById("countdown");
                    const resendButton = document.getElementById("resend-code");

                    const interval = setInterval(() => {
                      countdown--;
                      countdownElement.innerText = `(${countdown}s)`;

                      if (countdown === 0) {
                        resendButton.disabled = false;
                        resendButton.style.cursor = "pointer";
                        resendButton.style.color = "";
                        countdownElement.style.display = "none";
                        clearInterval(interval);
                      }
                    }, 1000);
                  })();
                </script>
                <button class="btn btn-primary" form="verify-form" type="submit">Authenticate</button>
              </div>
            </form>

            <!-- Resend Code Form -->
            <form method="POST" id="resend-code-form" class="d-none" action="{{ route('two-factor.resend') }}">
              @csrf
            </form>
          </div>
          <div class="card-footer text-center py-3">
            @if(!Auth::check())
            <div class="small | tw-text-blue-700"><a href="/register">Don't have an account yet? Create one now!</a></div>
            @else
            <a href="@if(Auth::user()->hasRole('Super Admin')) /superadmin/dashboard @elseif(Auth::user()->hasRole('Admin')) /admin/dashboard @elseif(Auth::user()->hasRole('Staff')) /staff/dashboard @elseif(Auth::user()->hasRole('Vendor')) /portal/vendor/dashboard @elseif(Auth::user()->hasRole('Driver')) /driver/dashboard @endif" class="text-primary">Go back to Dashboard</a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>


</x-layout.authTemplate>