<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white " href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon "><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="false">
        Vehicle Maintenance
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Maintenance Number: (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $maintenance->maintenanceNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-overflow-x-auto tw-mb-6">
      <!-- Maintenance Information -->
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Maintenance Update</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">Review and update the maintenance details of this vehicle.</p>
      </div>
      <div class="tw-px-4 tw-grid tw-grid-cols-2 tw-gap-2 tw-text-sm | max-md:tw-text-xs">
        <div>
          <label for="vehicleID" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vehicle ID</label>
          <input type="text" id="vehicleID" name="vehicleID" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $maintenance->vehicle_id }}" readonly>
        </div>
        <div>
          <label for="maintenanceNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Maintenance Number</label>
          <input type="text" id="maintenanceNumber" name="maintenanceNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $maintenance->maintenanceNumber }}" readonly>
        </div>
        <div>
          <label for="task" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Task</label>
          <input type="text" id="task" name="task" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $maintenance->task }}" readonly>
        </div>
        <div>
          <label for="amount" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Amount</label>
          <input type="text" id="amount" name="amount" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $maintenance->amount }}" readonly>
        </div>
        <div>
          <label for="scheduledDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Scheduled Date</label>
          <input type="text" id="scheduledDate" name="scheduledDate" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ \Carbon\Carbon::parse($maintenance->scheduled_date)->format('Y-m-d') }}" readonly>
        </div>
        <div>
          <label for="completedDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Completed Date</label>
          <input type="text" id="completedDate" name="completedDate" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $maintenance->completed_date ? \Carbon\Carbon::parse($maintenance->completed_date)->format('Y-m-d') : 'N/A' }}" readonly>
        </div>
        <div>
          <label for="conditionStatus" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Condition Status</label>
          <input type="text" id="conditionStatus" name="conditionStatus" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ ucfirst($maintenance->conditionStatus) }}" readonly>
        </div>
        <div>
          <label for="createdAt" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Created At</label>
          <input type="text" id="createdAt" name="createdAt" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $maintenance->created_at->format('Y-m-d') }}" readonly>
        </div>
      </div>
    </div>

    @if(\Carbon\Carbon::parse($maintenance->completed_date)->format('Y-m-d') <= now()->format('Y-m-d'))
    <div class="tw-flex tw-justify-end tw-mb-6">
      <form action="{{ route('staff.fleet.vehicle.markAsAvailable', $maintenance->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="tw-text-white tw-bg-gray-700 tw-text-sm tw-font-bold tw-py-2 tw-px-4 tw-rounded | max-md:tw-text-xs">Mark as Available</button>
      </form>
    </div>
    @endif

    <div>
      <div class="tw-flex tw-items-center tw-justify-start tw-my-6">
        <a href="{{ route('staff.fleet.vehicle.show', $maintenance->vehicle->id) }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-p-3 ">
          <i class="fa-solid fa-arrow-left tw-mr-2 | max-md:tw-text-xs"></i>
          Back
        </a>
      </div>
    </div>

    <hr>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2 | max-md:tw-text-sm">Order Table Instructions</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2 | max-md:tw-text-[12px]">
        <p class="tw-mb-1">In the table above, you can view and manage your order requests created by you by clicking on the "Order No." column of the table. When you click on the "Order No.", you will be redirected to the order details page. </p>
        <p class="tw-mt-2">To add a new order request, click the "Add New" button.</p>
      </div>
    </div>
  </div>

</x-layout.mainTemplate>