<x-layout.dashboardTemplate>
  <div class="container-fluid tw-my-5 | tw-px-4 max-sm:tw-px-0 ">
    <nav class="tw-flex | max-sm:justify-center" aria-label="Breadcrumb">
      <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
        <x-partials.breadcrumb href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
          Dashboard
        </x-partials.breadcrumb>

        <x-partials.breadcrumb :active="true" :isLast="true">
          Calendar and Schedule
        </x-partials.breadcrumb>
      </ol>
    </nav>

    <div class="tw-max-w-9xl tw-mx-auto tw-mt-3 tw-mb-6 tw-space-y-6">
    
    </div>

  </div>
</x-layout.dashboardTemplate>