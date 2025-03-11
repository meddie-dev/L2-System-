<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white " href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon "><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('staff.document.manage') }}" :active="false" :isLast="false">
        Document
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Review Document No.(<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $document->documentNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-overflow-x-auto tw-mb-6">
      <h3 class="tw-text-md tw-font-bold tw-my-4 tw-text-gray-400 | max-md:tw-text-sm">Vendor Information</h3>
      <div class="tw-px-4 tw-flex tw-flex-col tw-gap-2 tw-text-sm | max-md:tw-text-xs">
        <div>
          <label for="vendorID" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vendor ID</label>
          <input type="text" id="vendorID" name="vendorID" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $document->user->id ?? 'N/A' }}" readonly>
        </div>
        <div>
          <label for="vendorName" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Vendor Name</label>
          <input type="text" id="vendorName" name="vendorName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $document->user ? $document->user->firstName . ' ' . $document->user->lastName : 'N/A' }}" readonly>
        </div>
        <div>
          <label for="vendorEmail" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Email</label>
          <input type="text" id="vendorEmail" name="vendorEmail" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $document->user ? $document->user->email : 'N/A' }}" readonly>
        </div>
        <div>
          <label for="verificationStatus" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Verification Status</label>
          <input type="text" id="verificationStatus" name="verificationStatus" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $document->user ? ($document->user->two_factor_enabled ? 'Verified' : 'Not Verified') : 'N/A' }}" readonly>
        </div>
      </div>

      <!-- Custom Divider -->
      <div class="tw-flex tw-items-center tw-justify-center tw-mt-4">
        <div class="tw-mx-auto tw-my-6 tw-w-[30%] tw-h-[2px] tw-bg-slate-100"></div>
        <span class="tw-text-sm tw-font-bold tw-text-gray-200 | max-md:tw-text-xs">DS GLOBAL HOLDING INC.</span>
        <div class="tw-mx-auto tw-my-6 tw-w-[30%] tw-h-[2px] tw-bg-slate-100"></div>
      </div>

      <h3 class="tw-text-md tw-font-bold tw-my-4 tw-text-gray-400 | max-md:tw-text-sm">Order Information</h3>
      <div class="tw-flex tw-flex-col tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-grid-cols-1 max-md:tw-gap-2">
        <div>
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Order Number</label>
          <input type="text" id="orderNumber" name="orderNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $document->order->orderNumber ?? 'N/A' }}" readonly>
        </div>
      </div>

      <!-- Custom Divider -->
      <div class="tw-flex tw-items-center tw-justify-center tw-mt-4">
        <div class="tw-mx-auto tw-my-6 tw-w-[30%] tw-h-[2px] tw-bg-slate-100"></div>
        <span class="tw-text-sm tw-font-bold tw-text-gray-200 | max-md:tw-text-xs">DS GLOBAL HOLDING INC.</span>
        <div class="tw-mx-auto tw-my-6 tw-w-[30%] tw-h-[2px] tw-bg-slate-100"></div>
      </div>

      <h3 class="tw-text-md tw-font-bold tw-my-4 tw-text-gray-400 | max-md:tw-text-sm">Document Submissions</h3>
      <div class="tw-flex tw-flex-col tw-gap-2 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-grid-cols-1 max-md:tw-gap-2">
        <div class="tw-flex tw-items-center tw-justify-center tw-relative tw-mb-4">
          <img src="{{ asset('storage/' . $document->documentUrl) }}" alt="Document Image" class="tw-max-w-[500px]  tw-max-h-[500px] tw-rounded-md tw-shadow-md tw-object-fill">
          <div style="font-size: 2rem; left: 50%; top: 50%; transform: translate(-50%, -50%);" class="tw-absolute tw-z-10 tw-text-gray-700 tw-opacity-10 tw-font-bold">
            {{ $document->documentNumber ?? 'N/A' }}
          </div>
        </div>

        <div class="tw-grid tw-grid-cols-2 tw-gap-2">
          <div>
            <label for="documentNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Document Number</label>
            <input type="text" id="documentNumber" name="documentNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $document->documentNumber }}" readonly>
          </div>
          <div>
            <label for="documentName" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Document Name</label>
            <input type="text" id="documentName" name="documentName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $document->documentName }}" readonly>
          </div>
          <div>
            <label for="documentStatus" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Document Status</label>
            <input type="text" id="documentStatus" name="documentStatus" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ ucfirst($document->approval_status) }}" readonly>
          </div>
          <div>
            <label for="documentCreate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Document Create</label>
            <input type="text" id="documentCreate" name="documentCreate" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ optional($document->created_at)->format('Y-m-d') ?? 'N/A' }}" readonly>
          </div>
        </div>
      </div>
    </div>
    @if($document->approval_status !== 'approved')
    <div class="tw-flex tw-justify-end tw-mb-6">
      <form class="tw-mr-2" action="{{ route('staff.document.reject', $document->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="tw-text-white tw-bg-red-600 tw-text-sm tw-font-bold tw-py-2 tw-px-4 tw-rounded | max-md:tw-text-xs">Reject</button>
      </form>
      <form action="{{ route('staff.document.approve', $document->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="tw-text-white tw-bg-gray-700 tw-text-sm tw-font-bold tw-py-2 tw-px-4 tw-rounded | max-md:tw-text-xs">Mark as Reviewed</button>
      </form>
    </div>
    @endif
    <div>
      <div class="tw-flex tw-items-center tw-justify-start tw-my-6">
        <a href="{{ route('staff.document.manage') }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-p-3 ">
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