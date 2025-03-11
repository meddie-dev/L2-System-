<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between |  max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : (Auth::user()->hasRole('Staff') ? 'staff.dashboard' : 'driver.dashboard'))) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('admin.audit.index') }}" :active="false" :isLast="false">
        Report Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Review Report (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $incidentReport->reportNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-overflow-x-auto tw-mb-6">
      <!-- Report Information -->
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Report Information</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">View and track the details of this report.</p>
      </div>
      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-px-4 tw-text-sm tw-my-4 | max-md:tw-text-xs ">
        <div>
          <label for="reportNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Report Number:</label>
          <input type="text" id="reportNumber" name="reportNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER REPORT NUMBER" value="{{ $incidentReport->reportNumber ?? 'N/A' }}" readonly>
        </div>
        <div>
          <label for="incident_type" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Incident Type:</label>
          <input type="text" id="incident_type" name="incident_type" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER INCIDENT TYPE" value="{{ ucfirst($incidentReport->incident_type) ?? 'N/A' }}" readonly>
        </div>
      </div>

      <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs">
        <label for="proof" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Proof:</label>

        <div class="tw-px-4">
          <img src="{{ asset('storage/'.$incidentReport->proof) }}" alt="Proof" class="tw-w-full tw-h-96 tw-object-cover max-w-[500px]">
        </div>
      </div>

      <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-px-4 tw-text-sm tw-my-4 | max-md:tw-text-xs">
        <div>
          <label for="description" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Description:</label>
          <textarea id="description" name="description" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" rows="5" placeholder="ENTER DESCRIPTION" readonly>{{ $incidentReport->description ?? 'N/A' }}</textarea>
        </div>
      </div>

      @if($incidentReport->approval_status == 'approved')
      <!-- Reviewed By -->
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Reviewed By</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">View and track the details of this review.</p>
      </div>
      <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-gap-2">
        <div>
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Name</label>
          <input type="text" id="approvedByName" name="approvedByName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="APPROVED BY NAME" value="{{ optional(App\Models\User::find($incidentReport->reviewed_by))->firstName }} {{ optional(App\Models\User::find($incidentReport->reviewed_by))->lastName }}" readonly>
        </div>
        <div>
          <label for="product" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Approved At</label>
          <input type="text" id="product" name="product" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $incidentReport->updated_at->format('Y-m-d') }}" readonly>
        </div>
        <div>
          <label for="deliveryLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Role</label>
          <input type="text" id="deliveryLocation" name="deliveryLocation" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ optional(App\Models\User::find($incidentReport->reviewed_by))->roles->pluck('name')->first() ?? 'N/A' }}" readonly>
        </div>
      </div>

      <!-- Approved By -->
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Approved By</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">View and track the details of this approval.</p>
      </div>
      <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-gap-2">
        <div>
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Name</label>
          <input type="text" id="approvedByName" name="approvedByName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="APPROVED BY NAME" value="{{ optional(App\Models\User::find($incidentReport->approved_by))->firstName }} {{ optional(App\Models\User::find($incidentReport->approved_by))->lastName }}" readonly>
        </div>
        <div>
          <label for="product" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Approved At</label>
          <input type="text" id="product" name="product" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $incidentReport->updated_at->format('Y-m-d') }}" readonly>
        </div>
        <div>
          <label for="deliveryLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Role</label>
          <input type="text" id="deliveryLocation" name="deliveryLocation" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ optional(App\Models\User::find($incidentReport->approved_by))->roles->pluck('name')->first() ?? 'N/A' }}" readonly>
        </div>
      </div>
      @endif

      @if($incidentReport->approval_status != 'approved')
      <div class="tw-flex tw-justify-end tw-my-6">
        <form class="tw-mr-2" action="{{ route('admin.audit.report.rejected', $incidentReport->id) }}" method="POST">
          @csrf
          @method('PATCH')
          <button type="submit" class="tw-text-white tw-bg-red-600 tw-text-sm tw-font-bold tw-py-2 tw-px-4 tw-rounded | max-md:tw-text-xs">Reject</button>
        </form>
        <form action="{{ route('admin.audit.report.approved', $incidentReport->id) }}" method="POST">
          @csrf
          @method('PATCH')
          <button type="submit" class="tw-text-white tw-bg-gray-700 tw-text-sm tw-font-bold tw-py-2 tw-px-4 tw-rounded | max-md:tw-text-xs">Mark as Approved</button>
        </form>
      </div>
      @endif

      <div>
        <div class="tw-flex tw-items-center tw-justify-start tw-my-6">
          <a href="{{ route('admin.audit.index') }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-py-2 max-md:tw-px-4 max-md:tw-text-xs">
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