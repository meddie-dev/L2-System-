<x-layout.portal.mainTemplate>
  <nav class="tw-flex tw-justify-between |  max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : (Auth::user()->hasRole('Vendor') ? 'vendorPortal.dashboard' : 'staff.dashboard'))) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('vendorPortal.order.document') }}" :active="false" :isLast="false">
        Uploaded Documents
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Review Uploaded Document (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $document->documentNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    @if (Str::endsWith(strtolower($document->documentUrl), ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.webp']) && file_exists(public_path('storage/' . $document->documentUrl)))
    <div>
      <div class="tw-flex tw-items-center tw-justify-center tw-mb-4">
        <img src="{{ asset('storage/' . $document->documentUrl) }}"
          class="tw-w-full tw-h-auto tw-rounded-md tw-shadow-md tw-max-w-[500px]"
          alt="Image"> <!-- Use a generic alt text or dynamic based on context -->
      </div>
    </div>
    @else
    @endif
    <div class="tw-overflow-x-auto tw-mb-6">
      <!-- Order -->
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Order Information</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">View and track the details of this order.</p>
      </div>
      <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs ">
        <div>
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Order Number:</label>
          <input type="text" id="orderNumber" name="orderNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ $document->order->orderNumber ?? 'N/A' }}" readonly>
        </div>
      </div>

      <!-- Document -->
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Document Information</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">View and track the details of this document.</p>
      </div>
      <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-grid-cols-1 max-md:tw-gap-2">
        <div>
          <label for="documentName" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Document Name:</label>
          <input type="text" id="documentName" name="documentName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER DOCUMENT NAME" value="{{ $document->documentName }}" readonly>
        </div>
        <div>
          <label for="documentNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Document Number:</label>
          <input type="text" id="documentNumber" name="documentNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER DOCUMENT NUMBER" value="{{ strtoupper(Str::random(20)) }}" readonly>
        </div>
        <div>
          <label for="documentStatus" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Document Status:</label>
          <input type="text" id="documentStatus" name="documentStatus" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER DOCUMENT STATUS" value="{{ ucfirst($document->approval_status) }}" readonly>
        </div>
        <div>
          <label for="documentCreatedDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Document Created Date:</label>
          <input type="text" id="documentCreatedDate" name="documentCreatedDate" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER DOCUMENT CREATED DATE" value="{{ $document->created_at->setTimezone('Asia/Manila')->format('Y-m-d') }}" readonly>
        </div>
        <div>
          <label for="documentCreatedTime" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Document Created Time:</label>
          <input type="text" id="documentCreatedTime" name="documentCreatedTime" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER DOCUMENT CREATED TIME" value="{{ $document->created_at->setTimezone('Asia/Manila')->format('h:i A') }}" readonly>
        </div>
      </div>

      @if ($document->approval_status === 'reviewed')
      <!-- Reviewed By -->
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Reviewed By</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">Information about who reviewed the document.</p>
      </div>
      <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-gap-2">
        <div>
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Name</label>
          <input type="text" id="approvedByName" name="approvedByName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="APPROVED BY NAME" value="{{ optional(App\Models\User::find($document->reviewed_by))->firstName }} {{ optional(App\Models\User::find($document->reviewed_by))->lastName }}" readonly>
        </div>
        <div>
          <label for="product" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Reviewed At</label>
          <input type="text" id="product" name="product" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $document->updated_at->format('Y-m-d') }}" readonly>
        </div>
        <div>
          <label for="deliveryLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Role</label>
          <input type="text" id="deliveryLocation" name="deliveryLocation" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ optional(App\Models\User::find($document->reviewed_by))->roles->pluck('name')->first() }}" readonly>
        </div>
      </div>
      @endif
      <div>
        <div class="tw-flex tw-items-center tw-justify-start tw-my-6">
          <a href="{{ route('vendorPortal.order.document') }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-p-3 ">
            <i class="fa-solid fa-arrow-left tw-mr-2 | max-md:tw-text-xs"></i>
            Back
          </a>
        </div>
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

</x-layout.portal.mainTemplate>