<x-layout.dashboardTemplate>
  <div class="container-fluid px-4 tw-my-10">
    <nav class="tw-flex tw-mb-5 max-sm:justify-center" aria-label="Breadcrumb">
      <ol class="tw-inline-flex tw-items-center tw-space-x-1 md:tw-space-x-2 rtl:tw-space-x-reverse">
        <x-partials.breadcrumb href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
          <div class="sb-nav-link-icon tw-pr-2"><i class="fa-solid fa-table-columns"></i></div>
          Dashboard
        </x-partials.breadcrumb>

        <x-partials.breadcrumb :active="true" :isLast="true">
          Settings
        </x-partials.breadcrumb>
      </ol>
    </nav>

    <div class="tw-max-w-7xl tw-mx-auto tw-py-6 tw-space-y-6">

      <!-- Profile Section -->
      <div class="tw-bg-gray-200 tw-text-black tw-shadow sm:tw-rounded-lg tw-p-6">
        <h2 class="tw-text-xl tw-font-semibold">Account Settings</h2>
        <p class="tw-text-sm">Manage your profile details and personal information.</p>
      </div>

      <!-- Update Profile Form -->
      <div class="tw-bg-white tw-shadow sm:tw-rounded-lg tw-p-6">
        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Update Profile</h3>
        <form action="{{ route('settings.update') }}" method="POST" class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6 tw-mt-4">
          @csrf
          <!-- First Name -->
          <div>
            <label for="first_name" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">First Name</label>
            <input type="text" id="first_name" name="first_name" value="{{ Auth::user()->firstName }}" required
              class="tw-mt-1 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-sm:text-sm">
          </div>

          <!-- Last Name -->
          <div>
            <label for="last_name" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="{{ Auth::user()->lastName }}" required
              class="tw-mt-1 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-sm:text-sm">
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Email</label>
            <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required
              class="tw-mt-1 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-sm:text-sm">
          </div>

          <!-- Role -->
          <div>
            <label for="role" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Role</label>
            <input type="text" id="role" name="role" value="{{ Auth::user()->hasRole('Super Admin') ? 'Super Admin' : (Auth::user()->hasRole('Admin') ? 'Admin' : 'Staff') }}" required readonly
              class="tw-mt-1 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-sm:text-sm">
          </div>

          <!-- Update Button -->
          <div class="tw-col-span-1 md:tw-col-span-2 tw-flex tw-justify-end">
            <button type="submit" class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-blue-600 tw-text-white tw-text-sm tw-font-medium tw-rounded-md tw-shadow-sm hover:tw-bg-blue-700 tw-focus:outline-none tw-focus:ring-2 tw-focus:ring-blue-500 tw-focus:ring-offset-2">
              Update Profile
            </button>
          </div>
        </form>
      </div>

      <!-- Security Section -->
      <div class="tw-bg-gray-300 tw-text-black tw-shadow sm:tw-rounded-lg tw-p-6">
        <h2 class="tw-text-xl tw-font-semibold">Security Settings</h2>
        <p class="tw-text-sm">Manage your account security settings and add new password.</p>
      </div>

      <div class="tw-bg-white tw-shadow sm:tw-rounded-lg tw-p-6">
        <!-- Change Password Section -->
        <div>
          <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">Change Password</h3>
          <p class="tw-text-gray-600 tw-text-sm">Reset your password by entering your registered email. You'll receive a secure link to set a new password. Check spam if not received.</p>
          <form method="POST" action="{{ route('password.email') }}" class="tw-grid tw-grid-cols-1 tw-gap-6 tw-mt-4">
            @csrf
            <div>
              <label for="email" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Email</label>
              <input id="email" type="email" name="email" value="{{ Auth::user()->email }}" required
                class="tw-mt-1 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-sm:text-sm @error('email') is-invalid @enderror">
              @error('email')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>

            <div class="tw-col-span-1 md:tw-col-span-2 tw-flex tw-justify-end">
              <button type="submit" class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-blue-600 tw-text-white tw-text-sm tw-font-medium tw-rounded-md tw-shadow-sm hover:tw-bg-blue-700 tw-focus:outline-none tw-focus:ring-2 tw-focus:ring-blue-500 tw-focus:ring-offset-2">
                Change Password
              </button>
            </div>
          </form>
        </div>

        <!-- Two-Factor Authentication Section -->
        <div>
          <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900">
            Two-Factor Authentication 
            @if(Auth::user()->two_factor_enabled==true)
                <i class="fa-regular fa-circle-check tw-text-green-500"></i>
            @else
                <span class="tw-text-black">(Required)</span>
            @endif
          </h3>
          <p class="tw-text-gray-600 tw-text-sm">Add an extra layer of security to your account by enabling two-factor authentication.</p>
          <form method="POST" 
                action="{{ route(Auth::user()->two_factor_enabled==true ? 'two-factor.disable' : 'two-factor.send') }}" class="tw-mt-4 tw-flex tw-justify-end">
            @csrf
            <button type="submit" class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 @if(Auth::user()->two_factor_enabled==true) tw-bg-red-600 @else tw-bg-blue-600 @endif tw-text-white tw-text-sm tw-font-medium tw-rounded-md tw-shadow-sm hover:tw-bg-blue-700 tw-focus:outline-none tw-focus:ring-2 tw-focus:ring-blue-500 tw-focus:ring-offset-2">
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
      <div class="tw-bg-white tw-shadow sm:tw-rounded-lg tw-p-6">
        <h2 class="tw-text-xl tw-font-semibold tw-text-gray-900">Account Deletion</h2>
        <p class="tw-text-gray-600 tw-text-sm tw-mb-4">
          Manage your account deletion. Please note that deleting your account will remove all your data from our system and it's irreversible.
        </p>
        <div class="tw-col-span-1 md:tw-col-span-2 tw-flex tw-justify-end">
          <a href="{{route('settings.delete-view')}}" class="tw-inline-flex tw-items-center tw-px-5 tw-py-2 tw-bg-red-600 tw-text-white tw-text-sm tw-font-medium tw-rounded-md tw-shadow-sm hover:tw-bg-red-700 hover:tw-text-white tw-focus:outline-none tw-focus:ring-2 tw-focus:ring-red-500 tw-focus:ring-offset-2">
            Delete Account
          </a>
        </div>
      </div>
    </div>

  </div>
</x-layout.dashboardTemplate>