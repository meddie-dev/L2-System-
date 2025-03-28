<x-layout.mainTemplate>
  <div class="container-fluid tw-my-5 tw-px-4 max-sm:tw-px-0">
    <nav class="tw-flex max-sm:justify-center" aria-label="Breadcrumb">
      <ol class="tw-inline-flex tw-items-center tw-space-x-1 md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
        <x-partials.breadcrumb href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
          Dashboard
        </x-partials.breadcrumb>

        <x-partials.breadcrumb :active="true" :isLast="true">
          Back Up List
        </x-partials.breadcrumb>
      </ol>
    </nav>

    <div class="card-body tw-px-4">
      <div class="tw-overflow-x-auto">
        <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4 max-sm:tw-text-sm">
          <thead class="tw-bg-gray-200 tw-text-gray-700">
            <tr>
              <th class="tw-px-4 tw-py-2">Backup Name</th>
              <th class="tw-px-4 tw-py-2">Created At</th>
            </tr>
          </thead>

          <tbody id="reportRecords" class="tw-bg-white">
            @foreach($backups as $backup)
            <tr class="hover:tw-bg-gray-100">
              <td class="tw-px-4 tw-py-2">
                <form action="{{ route('restore-backup') }}" method="POST" class="inline">
                  @csrf
                  <input type="hidden" name="file_id" value="{{ $backup['id'] }}">
                  <button type="submit" class="tw-text-blue-600 hover:tw-underline border-none bg-transparent">
                    Restore {{ $backup['name'] }}
                  </button>
                </form>
              </td>
              <td class="tw-px-4 tw-py-2">
                {{ \Carbon\Carbon::parse($backup['createdTime'])->format('Y-m-d') }}
              </td>

            </tr>
            @endforeach
          </tbody>
        </table>

        <hr>
      </div>
      <div>
        <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2 max-md:tw-text-sm">Backup Table Instructions</h3>
        <div class="tw-text-xs tw-text-gray-600 tw-mb-2 max-md:tw-text-[11px]">
          <p class="tw-mb-1">In the table above, you can view and restore backups created by you by clicking on the "Restore" button. When you click on the "Restore" button, you will be redirected to the restore backup page.</p>
        </div>
      </div>
    </div>
  </div>
</x-layout.mainTemplate>