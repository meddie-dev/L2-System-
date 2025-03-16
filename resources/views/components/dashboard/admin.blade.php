<x-layout.dashboardTemplate>
<div class="container-fluid tw-my-10 tw-px-4 | max-sm:tw-px-0 max-sm:tw-my-5">
    <nav class="tw-flex tw-mb-5 | max-sm:flex-start" aria-label="Breadcrumb">
      <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse">
        <x-partials.breadcrumb href="{{ (Auth::user()->hasRole('Super Admin')) ? '/superadmin/dashboard' : ((Auth::user()->hasRole('Admin')) ? '/admin/dashboard' : '/staff/dashboard') }}" :active="true" :isLast="true">
          <div class="sb-nav-link-icon tw-pr-2 | max-sm:tw-text-sm"><i class="fa-solid fa-table-columns | max-sm:tw-text-sm"></i>
            Dashboard
          </div>
        </x-partials.breadcrumb>
      </ol>
    </nav>

    @if (Auth::user()->two_factor_enabled === 0)
    <div class="tw-bg-yellow-100 tw-border tw-border-yellow-400 tw-p-4 tw-rounded tw-mb-4 | max-sm:tw-py-2">
      <p class="tw-text-sm tw-text-yellow-700 tw-font-semibold | max-sm:tw-text-[12px]">
        <i class="fa-solid fa-circle-info tw-mr-1"></i>
        <span class="tw-font-bold">Notice:</span> You have not enabled Two-Factor Authentication yet. <a href="{{ route('settings.index') }}" class="tw-underline">Click here</a> to enable it now.
      </p>
    </div>
    @endif

    <div class="tw-max-w-9xl tw-mx-auto tw-mb-10 tw-bg-white tw-rounded-lg tw-shadow-lg tw-p-8 | max-sm:tw-p-4 max-sm:tw-my-6"
      data-aos="fade">
      <div>
        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-600 tw-mb-4  | max-sm:tw-mb-2 max-sm:tw-text-[16px]">Announcement:</h3>
        <p class="tw-text-sm tw-text-gray-500 tw-indent-14 max-sm:tw-text-[12px] max-sm:tw-text-justify max-sm:tw-indent-5">
          Welcome, {{ Auth::user()->firstName }}!, We're glad you're here! We're excited to announce that we've released a new feature to help you manage your orders more efficiently! You can now view your order statistics over the past 12 months and get insights on how to improve your business. Check it out and let us know what you think!
        </p>
      </div>
    </div>

    <div class="row tw-text-center | max-sm:tw-hidden " data-aos="fade">
      <div class="col-xl-4 col-md-6">
        <div class="card tw-bg-[#212529] text-white mb-4">
          <div class="card-body">
            <p class="tw-text-sm tw-font-semibold">Total Orders Created: </p>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-md-6">
        <div class="card tw-bg-[#212529] text-white mb-4">
          <div class="card-body">
            <p class="tw-text-sm tw-font-semibold">Total Documents Uploaded: </p>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-md-6">
        <div class="card tw-bg-[#212529] text-white mb-4">
          <div class="card-body">
            <p class="tw-text-sm tw-font-semibold">Total Payments Completed: </p>
          </div>
        </div>
      </div>
    </div>

    <div class="tw-bg-white tw-rounded-lg tw-shadow-lg tw-p-4 | max-sm:tw-p-2 max-sm:tw-text-sm">
      <p class="tw-text-sm tw-text-gray-500 tw-mb-4 tw-font-semibold | max-sm:tw-text-[12px]  max-sm:tw-text-center max-sm:tw-my-2">Below is a list of your order requests that are currently in shipment:</p>
      <div class="card-body tw-px-4">
        <div class="tw-overflow-x-auto ">
          <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4">

            <thead class="tw-bg-gray-200 tw-text-gray-700 ">
              <tr>
                <th class="tw-px-4 tw-py-2">Shipment No. </th>
                <th class="tw-px-4 tw-py-2">Status</th>
              </tr>
            </thead>

            <tbody id="orderRecords" class="tw-bg-white">
             
            </tbody>
          </table>

        </div>
      </div>
    </div>

  </div>
</x-layout.dashboardTemplate>