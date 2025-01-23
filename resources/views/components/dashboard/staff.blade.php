<x-layout.dashboardTemplate>
  <div class="container-fluid tw-my-10 px-4">
    <nav class="tw-flex tw-mb-5 max-sm:justify-center" aria-label="Breadcrumb">
      <ol class="tw-inline-flex tw-items-center tw-space-x-1 md:tw-space-x-2 rtl:tw-space-x-reverse">
        <x-partials.breadcrumb href="/supplier/dashboard" :active="true" :isLast="true">
          <div class="sb-nav-link-icon tw-pr-2"><i class="fa-solid fa-table-columns"></i></div>
          Dashboard
        </x-partials.breadcrumb>
      </ol>
    </nav>

   
  </div>
</x-layout.dashboardTemplate>