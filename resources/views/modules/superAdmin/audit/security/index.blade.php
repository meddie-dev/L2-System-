<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="false">
        Audit Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        Security
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-overflow-x-auto ">
      <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4 | max-sm:tw-text-sm ">

        <thead class="tw-bg-gray-200 tw-text-gray-700 ">
          <tr>
            <th class="tw-px-4 tw-py-2">ID</th>
            <th class="tw-px-4 tw-py-2">Name</th>
            <th class="tw-px-4 tw-py-2">Email</th>
            <th class="tw-px-4 tw-py-2">Created At</th>
            <th class="tw-px-4 tw-py-2">Active Status</th>

          </tr>
        </thead>

        <tbody id="reportRecords" class="tw-bg-white">
          @foreach($users as $user)
          <tr class="hover:tw-bg-gray-100">
            <td class="tw-px-4 tw-py-2">{{ $user->id }}</td>
            <td class="tw-px-4 tw-py-2">
              <a href="{{ route('superadmin.audit.show', $user->id) }}" class="hover:tw-underline tw-text-blue-700">
                {{ $user->firstName }} {{ $user->lastName }}
              </a>
            </td>
            <td class="tw-px-4 tw-py-2">{{ $user->email }}</td>
            <td class="tw-px-4 tw-py-2">{{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d')  }}</td>
            <td class="tw-px-4 tw-py-2">
              @if(\Carbon\Carbon::parse($user->last_active_at)->gt(\Carbon\Carbon::now()->subMinutes(5)))
              <span class="tw-text-green-600 tw-text-sm">Active</span>
              @else
              <span class="tw-text-{{ $user->last_active_at && \Carbon\Carbon::parse($user->last_active_at)->diffInDays() < 6 ? 'yellow-500' : 'red-500' }}">
                {{ $user->last_active_at ? \Carbon\Carbon::parse($user->last_active_at)->diffForHumans() : 'N/A' }}
              </span>
              @endif
            </td>

          </tr>
          @endforeach
        </tbody>
      </table>
      <hr>
    </div>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2 | max-md:tw-text-sm">Order Table Instructions</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2 | max-md:tw-text-[12px]">
        <p class="tw-mb-1">In the table above, you can view and manage your order requests created by you by clicking on the "Order No." column of the table. When you click on the "Order No.", you will be redirected to the order details page. </p>
        <p class="tw-mt-2">To add a new order request, click the "Add New" button.</p>
      </div>
    </div>
  </div>

</x-layout.mainTemplate>