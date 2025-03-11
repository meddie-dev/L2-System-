<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between |  max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : (Auth::user()->hasRole('Staff') ? 'staff.dashboard' : 'driver.dashboard'))) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('admin.audit.activity.index') }}" :active="false" :isLast="false">
        Review Activities
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Staff (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $user-> firstName }} {{ $user-> lastName }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  @php
  $events = $logs->pluck('event')->toArray();
  @endphp

  @if (collect($events)->contains(fn($event) => strpos($event, 'Unauthorized access attempt') !== false))
  <div class="tw-bg-yellow-100 tw-border tw-border-yellow-400 tw-p-4 tw-rounded tw-mb-4 | max-sm:tw-py-2">
    <p class="tw-text-sm tw-text-yellow-700 tw-font-semibold | max-sm:tw-text-[12px]">
      <i class="fa-solid fa-circle-info tw-mr-1"></i>
      <span class="tw-font-bold">Security Alert:</span> We have detected an <b>Unauthorized access attempt</b> by {{ $user-> firstName }} {{ $user-> lastName }}.
    </p>
  </div>
  @endif

  <div class="card-body tw-px-4">
    <!-- Staff Information -->
    <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
      <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Staff Information</h2>
      <p class="tw-text-xs | max-md:tw-text-xs">View and track the details of {{ $user->firstName }} {{ $user->lastName }}.</p>
    </div>
    <div>
      <table class="datatable  tw-mb-4 tw-w-full">
        <thead>
          <tr class="tw-bg-gray-200 tw-text-gray-700">
            <th class="tw-px-4 tw-py-2">Model</th>
            <th class="tw-px-4 tw-py-2">Pending</th>
            <th class="tw-px-4 tw-py-2">Reviewed</th>
            <th class="tw-px-4 tw-py-2">Rejected</th>
          </tr>
        </thead>
        <tbody>
          @foreach($models as $index => $model)
          <tr class="hover:tw-bg-gray-100">
            <td class="tw-px-4 tw-py-2">{{ class_basename($model) }}</td>
            <td class="tw-px-4 tw-py-2">{{ $model::where('approval_status', 'pending')->where('assigned_to', $user->id)->count() }}</td>
            <td class="tw-px-4 tw-py-2">{{ $model::where('approval_status', '!=', 'pending')->where('assigned_to', $user->id)->count() }}</td>
            <td class="tw-px-4 tw-py-2">{{ $model::where('approval_status', 'rejected')->where('assigned_to', $user->id)->count() }}</td>
          </tr>
          @endforeach
          <tr class="tw-bg-gray-100 tw-font-bold">
            <td class="tw-px-4 tw-py-2"><span class="tw-font-bold">Total</span></td>
            <td class="tw-px-4 tw-py-2"><span class="tw-font-bold">{{ $pendingCounts }}</span></td>
            <td class="tw-px-4 tw-py-2"><span class="tw-font-bold">{{ $reviewedCounts }}</span></td>
            <td class="tw-px-4 tw-py-2"><span class="tw-font-bold">{{ $rejectedCounts }}</span></td>
          </tr>
        </tbody>
      </table>

    </div>

    <!-- Activity Logs -->
    <div class="tw-bg-white | max-sm:tw-text-sm ">
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Activity Information</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">View and track the details of this activity.</p>
      </div>
      <div class="card-body tw-max-h-[calc(100vh-23rem)]">
        <div class="tw-overflow-y-auto tw-max-h-[calc(100vh-23rem)] tw-mb-4">
          <div class="tw-container tw-mx-auto tw-p-6">
            <div class="tw-relative tw-border-l-4 tw-border-gray-500 tw-ml-1">
              @foreach ($logs as $log)
              <div class="tw-mb-6 tw-ml-6">
                @if (strpos($log->event, 'Unauthorized access attempt') !== false)
                <div class="tw-absolute tw-w-4 tw-h-4 tw-bg-[#f7808a] tw-rounded-full tw--left-[10px] tw-mt-8"></div>
                @else
                <div class="tw-absolute tw-w-4 tw-h-4 tw-bg-gray-500 tw-rounded-full tw--left-[10px] tw-mt-8"></div>
                @endif
                <div class="tw-bg-white tw-shadow-md tw-rounded-lg tw-p-4" style="background-color: {{ strpos($log->event, 'Unauthorized access attempt') !== false ? '#f8d7da' : '#ffffff' }};">
                  <div class="tw-flex tw-justify-between">
                    <p class="tw-text-sm tw-text-gray-600">{{ $log->created_at->format('M d, Y') }}</p>
                    <p class="tw-text-sm tw-text-gray-600">{{ $log->created_at->format('H:i') }}</p>
                  </div>
                  <p class="tw-text-gray-700">{{ $log->event }}</p>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="tw-flex tw-items-center tw-justify-start tw-my-6">
        <a href="{{ route('admin.audit.activity.index') }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-p-3 ">
          <i class="fa-solid fa-arrow-left tw-mr-2 | max-md:tw-text-xs"></i>
          Back
        </a>
      </div>
    </div>
    <hr>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2 | max-md:tw-text-sm">Review After Submission</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2 | max-md:tw-text-[11px]">
        <p class="tw-mb-1">Once the order request is submitted, you will receive an email notification. Please check your email for further instructions. The Staff will review your order request and provide you with a response within 24 hours or as soon as possible.</p>
      </div>
    </div>
  </div>
</x-layout.mainTemplate>