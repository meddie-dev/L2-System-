<x-layout.dashboardTemplate>
  <div class="container-fluid tw-my-10 tw-px-4 | max-sm:tw-px-0 max-sm:tw-my-5">
    <nav class="tw-flex tw-mb-5 | max-sm:flex-start" aria-label="Breadcrumb">
      <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse">
        <x-partials.breadcrumb href="{{ route('vendorPortal.dashboard') }}" :active="true" :isLast="true">
          <div class="sb-nav-link-icon tw-pr-2 | max-sm:tw-text-sm"><i class="fa-solid fa-table-columns | max-sm:tw-text-sm"></i>
            Dashboard
          </div>
        </x-partials.breadcrumb>
      </ol>
    </nav>

    <div class="tw-max-w-9xl tw-mx-auto tw-my-10 tw-bg-white tw-rounded-lg tw-shadow-lg tw-p-8 | max-sm:tw-p-4 max-sm:tw-my-6"
      data-aos="fade">
      <div>
        <h3 class="tw-text-xl tw-font-semibold tw-text-gray-700 tw-mb-4  | max-sm:tw-mb-2 max-sm:tw-text-[16px]">Hello, {{ Auth::user()->firstName }}!</h3>
        <p class="tw-text-sm tw-text-gray-500 tw-indent-14 max-sm:tw-text-[12px] max-sm:tw-text-justify max-sm:tw-indent-5">
          Welcome to the Super Admin Dashboard! From here, you can manage vendor approvals, review orders, access vendor profiles, monitor audit trails, manage fleet maintenance, oversee vehicle reservations, track documents, and more. If you have any questions or need any assistance, please don't hesitate to reach out to us. We're here to help!
        </p>
      </div>
    </div>

  </div>
</x-layout.dashboardTemplate>