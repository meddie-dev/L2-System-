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
          Driver (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $user-> firstName }} {{ $user-> lastName }}</span>)
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
    <!-- Driver Information -->
    <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Driver Information</h3>
    <div>
      <table class="datatable  tw-mb-4 tw-w-full">
        <thead>
          <tr class="tw-bg-gray-200 tw-text-gray-700">
            <th class="tw-px-4 tw-py-2">Performance Score</th>
            <th class="tw-px-4 tw-py-2">Average Rating</th>
            <th class="tw-px-4 tw-py-2">On Time Deliveries</th>
            <th class="tw-px-4 tw-py-2">Late Deliveries</th>
            <th class="tw-px-4 tw-py-2">Early Deliveries</th>
            <th class="tw-px-4 tw-py-2">Total Deliveries</th>
          </tr>
        </thead>
        <tbody>
          <tr class="hover:tw-bg-gray-100">
            <td class="tw-px-4 tw-py-2">{{ $user->performance_score }}</td>
            <td class="tw-px-4 tw-py-2">

              <span class="tw-text-sm tw-font-bold tw-inline-block tw-text-gray-500">
                @for ($i = 0; $i < 5; $i++)
                  @if ($i < floor($user->tripTickets()->avg('rating')))
                  <span style="color: #3490dc;">&#9733;</span>
                  @else
                  <span style="color: #6c757d;">&#9734;</span>
                  @endif
                  @endfor
              </span>
              {{ number_format($user->tripTickets()->avg('rating'), 1) }}/5
            </td>
            <td class="tw-px-4 tw-py-2">{{ $user->on_time_deliveries }}</td>
            <td class="tw-px-4 tw-py-2">{{ $user->late_deliveries }}</td>
            <td class="tw-px-4 tw-py-2">{{ $user->early_deliveries }}</td>
            <td class="tw-px-4 tw-py-2">{{ $user->tripTickets()->count() + $user->total_deliveries }}</td>
          </tr>
        </tbody>
      </table>

      <!-- Latest Feedback -->
      <div class="tw-mt-4">
        <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
          <h3 class="tw-text-md tw-font-bold | max-md:tw-text-sm">Latest Feedback:</h3>
          @if ($tripTicket && $tripTicket->rating !== null)
          <p class="tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">
            from:
            <span class="tw-font-semibold tw-text-gray-700">
            {{ $tripTicket->order->user->firstName }} {{ $tripTicket->order->user->lastName }}
            </span>
          </p>
          @endif
        </div>
        <div class="tw-border-2 tw-rounded-lg tw-p-4 @if ($tripTicket && $tripTicket->rating >= 4) tw-bg-green-100 tw-border-green-500 @elseif ($tripTicket && $tripTicket->rating < 4 && $tripTicket->rating >= 3) tw-bg-yellow-100 tw-border-yellow-500 @else tw-bg-red-100 tw-border-red-500 @endif">
          @if ($tripTicket && $tripTicket->rating !== null)
          <p class="tw-text-sm tw-text-gray-700 | max-md:tw-text-xs">
            "{{ $tripTicket->feedback }}"
          </p>
          <div class="tw-flex tw-items-center tw-space-x-1 tw-my-2">
            @for ($i = 0; $i < 5; $i++)
              @if ($i < floor($tripTicket->rating))
              <span style="color: #3490dc;">&#9733;</span>
              @else
              <span style="color: #6c757d;">&#9734;</span>
              @endif
            @endfor
            <span class="tw-text-sm tw-text-gray-500 tw-ml-2">{{ number_format($tripTicket->rating, 1) }}/5.0</span>
          </div>
          @else
          <p class="tw-text-sm tw-text-gray-500">No feedback available yet.</p>
          @endif
        </div>
      </div>
    </div>

    <!-- Custom Divider -->
    <div class="tw-flex tw-items-center tw-justify-center tw-mt-4">
      <div class="tw-mx-auto tw-my-6 tw-w-[30%] tw-h-[2px] tw-bg-slate-100"></div>
      <span class="tw-text-sm tw-font-bold tw-text-gray-200 | max-md:tw-text-xs">DS GLOBAL HOLDING INC.</span>
      <div class="tw-mx-auto tw-my-6 tw-w-[30%] tw-h-[2px] tw-bg-slate-100"></div>
    </div>

    <!-- Activity Logs -->
    <div class="tw-bg-white | max-sm:tw-text-sm ">
      <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Activity Information</h3>
      <div class="card-body tw-max-h-[calc(100vh-23rem)]">
        <div class="tw-overflow-y-auto tw-max-h-[calc(100vh-23rem)] tw-mb-4">
          <div class="tw-container tw-mx-auto tw-p-6">
            <div class="tw-relative tw-border-l-4 tw-border-blue-500 tw-ml-1">
              @foreach ($logs as $log)
              <div class="tw-mb-6 tw-ml-6">
                @if (strpos($log->event, 'Unauthorized access attempt') !== false)
                <div class="tw-absolute tw-w-4 tw-h-4 tw-bg-[#f7808a] tw-rounded-full tw--left-[10px] tw-mt-8"></div>
                @else
                <div class="tw-absolute tw-w-4 tw-h-4 tw-bg-blue-500 tw-rounded-full tw--left-[10px] tw-mt-8"></div>
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