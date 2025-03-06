<x-layout.dashboardTemplate>
  <div class="container-fluid tw-my-5 | tw-px-4 max-sm:tw-px-0 ">
    <nav class="tw-flex | max-md:tw-hidden" aria-label="Breadcrumb">
      <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
        <x-partials.breadcrumb href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : (Auth::user()->hasRole('Staff') ? 'staff.dashboard' : (Auth::user()->hasRole('Vendor') ? 'vendorPortal.dashboard' : 'driver.dashboard')))) }}" :active="false" :isLast="false">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
          Dashboard
        </x-partials.breadcrumb>

        <x-partials.breadcrumb :active="true" :isLast="true">
          Settings
        </x-partials.breadcrumb>
      </ol>
    </nav>

    <div class="tw-max-w-9xl tw-mx-auto tw-mt-3 tw-mb-6 tw-space-y-6">

      <!-- Profile Section -->
      <div class="tw-bg-gray-200 tw-text-black tw-shadow tw-p-6 sm:tw-rounded-lg max-md:tw-p-4">
        <h2 class="tw-text-xl tw-font-semibold | max-md:tw-text-sm">Account Settings</h2>
        <p class="tw-text-sm | max-md:tw-text-xs">Manage your profile details and personal information.</p>
      </div>

      <!-- Update Profile Form -->
      <div class="tw-bg-white tw-shadow tw-rounded tw-p-6 | max-md:tw-p-4">
        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900 | max-sm:tw-text-sm">Update Profile</h3>
        <form action="{{ route('settings.update') }}" method="POST" class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6 tw-mt-4">
          @csrf
          <!-- First Name -->
          <div>
            <label for="first_name" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs ">First Name</label>
            <input type="text" id="first_name" name="first_name" value="{{ Auth::user()->firstName }}" required
              class="tw-mt-1 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-sm:text-sm @error('first_name') is-invalid @enderror | max-md:tw-text-xs">
            @error('first_name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Last Name -->
          <div>
            <label for="last_name" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="{{ Auth::user()->lastName }}" required
              class="tw-mt-1 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-sm:text-sm @error('last_name') is-invalid @enderror | max-md:tw-text-xs">
            @error('last_name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Email</label>
            <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required
              class="tw-mt-1 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-sm:text-sm @error('email') is-invalid @enderror | max-md:tw-text-xs">
            @error('email')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Role -->
          <div>
            <label for="role" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Role</label>
            <input type="text" id="role" name="role" value="{{ Auth::user()->roles->pluck('name')->first() }}" readonly
              class="tw-mt-1 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-sm:text-sm @error('role') is-invalid @enderror | max-md:tw-text-xs">
            @error('role')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          @if (Auth::user()->hasRole('Driver'))
          <div>
            <label for="driverType" class="tw-block tw-text-sm tw-font-medium  | max-md:tw-text-xs"> Driver Type
              <span class=" {{ Auth::user()->driverType === null ? 'tw-text-red-500' : 'tw-text-green-500' }}">
                {{ Auth::user()->driverType === null ? '(Required)' : '(Assigned)' }}
              </span>
            </label>
            @if (Auth::user()->driverType === null)
              <select id="driverType" name="driverType" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('driverType') is-invalid @enderror" required>
                <option value="">Select vehicle type</option>
                <option value="light">Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)</option>
                <option value="medium">Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)</option>
                <option value="heavy">Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)</option>
              </select>
            @else
              <div style="color: gray">
                 <input type="text" id="driverType" name="driverType" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('driverType') is-invalid @enderror" value="{{ Auth::user()->driverType === 'light' ? 'Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)' : (Auth::user()->driverType === 'medium' ? 'Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)' : (Auth::user()->driverType === 'heavy' ? 'Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)' : 'N/A')) }}" readonly/>
              </div>
            @endif
            @error('driverType')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>
          @endif

          <!-- Update Button -->
          <div class="tw-col-span-1 md:tw-col-span-2 tw-flex tw-justify-end">
            <button type="submit" class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-blue-600 tw-text-white tw-text-sm tw-font-medium tw-rounded-md tw-shadow-sm hover:tw-bg-blue-700 tw-focus:outline-none tw-focus:ring-2 tw-focus:ring-blue-500 tw-focus:ring-offset-2 | max-md:tw-text-xs">
              Update Profile
            </button>
          </div>
        </form>
      </div>

      <!-- Security Section -->
      <div class="tw-bg-gray-300 tw-text-black tw-shadow tw-rounded sm:tw-rounded-lg tw-p-6 | max-md:tw-p-4">
        <h2 class="tw-text-xl tw-font-semibold | max-sm:tw-text-sm">Security Settings</h2>
        <p class="tw-text-sm | max-md:tw-text-xs">Manage your account security settings and add new password.</p>
      </div>

      <div class="tw-bg-white tw-shadow tw-rounded tw-p-6 | max-md:tw-p-4">
        <!-- Change Password Section -->
        <div class=" | max-sm:tw-mb-4">
          <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900 | max-md:tw-text-sm">Change Password</h3>
          <p class="tw-text-gray-600 tw-text-sm | max-md:tw-text-xs">Reset your password by entering your registered email. You'll receive a secure link to set a new password. Check spam if not received.</p>
          <form method="POST" action="{{ route('password.email') }}" class="tw-grid tw-grid-cols-1 tw-gap-6 tw-mt-4">
            @csrf
            <div>
              <label for="email" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Email</label>
              <input id="email" type="email" name="email" value="{{ Auth::user()->email }}" required
                class="tw-mt-1 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-sm:text-sm | max-md:tw-text-xs @error('email') is-invalid @enderror">
              @error('email')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>

            <div class="tw-col-span-1 md:tw-col-span-2 tw-flex tw-justify-end">
              <button type="submit" class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-blue-600 tw-text-white tw-text-sm tw-font-medium tw-rounded-md tw-shadow-sm hover:tw-bg-blue-700 tw-focus:outline-none tw-focus:ring-2 tw-focus:ring-blue-500 tw-focus:ring-offset-2 | max-md:tw-text-xs">
                Change Password
              </button>
            </div>
          </form>
        </div>

        <!-- Two-Factor Authentication Section -->
        <div>
          <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900 | max-md:tw-text-sm">
            Two-Factor Authentication
            @if(Auth::user()->two_factor_enabled==true)
            <i class="fa-regular fa-circle-check tw-text-green-500"></i>
            @else
            <span class="tw-text-black">(Required)</span>
            @endif
          </h3>
          <p class="tw-text-gray-600 tw-text-sm | max-md:tw-text-xs">Add an extra layer of security to your account by enabling two-factor authentication.</p>
          <form method="POST"
            action="{{ route(Auth::user()->two_factor_enabled==true ? 'two-factor.disable' : 'two-factor.send') }}" class="tw-mt-4 tw-flex tw-justify-end">
            @csrf
            <button type="submit" class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 @if(Auth::user()->two_factor_enabled==true) tw-bg-red-600 @else tw-bg-blue-600 @endif tw-text-white tw-text-sm tw-font-medium tw-rounded-md tw-shadow-sm hover:tw-bg-blue-700 tw-focus:outline-none tw-focus:ring-2 tw-focus:ring-blue-500 tw-focus:ring-offset-2 | max-md:tw-text-xs">
              @if(Auth::user()->two_factor_enabled==true)
              Turn Off
              @else
              Enable
              @endif Two-Factor Authentication
            </button>
          </form>
        </div>
      </div>

      <!-- Deletion Section -->
      <div class="tw-bg-white tw-shadow tw-rounded tw-p-6 | max-md:tw-p-4">
        <h2 class="tw-text-xl tw-font-semibold tw-text-gray-900 | max-md:tw-text-sm">Account Deletion</h2>
        <p class="tw-text-gray-600 tw-text-sm tw-mb-4 | max-md:tw-text-xs">
          Manage your account deletion. Please note that deleting your account will remove all your data from our system and it's irreversible.
        </p>
        <div class="tw-col-span-1 md:tw-col-span-2 tw-flex tw-justify-end">
          <a href="{{route('settings.delete-view')}}" class="tw-inline-flex tw-items-center tw-px-5 tw-py-2 tw-bg-red-600 tw-text-white tw-text-sm tw-font-medium tw-rounded-md tw-shadow-sm hover:tw-bg-red-700 hover:tw-text-white tw-focus:outline-none tw-focus:ring-2 tw-focus:ring-red-500 tw-focus:ring-offset-2 | max-md:tw-text-xs">
            Delete Account
          </a>
        </div>
      </div>
    </div>

  </div>
</x-layout.dashboardTemplate>