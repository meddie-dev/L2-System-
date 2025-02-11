<x-layout.portal.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-sm:justify-center" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
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
    <div>
      <div class="tw-flex tw-items-center tw-justify-center tw-mb-4">
        <img src="{{ asset('storage/' . $document->documentUrl) }}" alt="Document Image" class="tw-w-full tw-h-auto tw-rounded-md tw-shadow-md tw-max-w-[800px]">
      </div>      
    </div>
    <div class="tw-overflow-x-auto tw-mb-6">
      <h3 class="tw-text-md tw-font-bold tw-my-4">Order Information</h3>
      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-px-4 tw-text-sm ">
        <div>
          <p class=" tw-font-semibold">Order Number:</p>
          <p class="tw-text-gray-700">{{ $document->order->orderNumber ?? 'N/A' }}</p>
        </div>
      </div>
      <h3 class="tw-text-md tw-font-bold tw-my-4">Document Information</h3>
      <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-px-4 tw-text-sm ">
        <div>
          <p class="tw-font-semibold">Document Name:</p>
          <p class="tw-text-gray-700">{{ $document->documentName }}</p>
        </div>
        <div>
          <p class="tw-font-semibold">Document Number:</p>
          <p class="tw-text-gray-700">{{ $document->documentNumber }}</p>
        </div>
        <div>
          <p class="tw-font-semibold">Document Status:</p>
          <p class="tw-text-gray-700">{{ ucfirst($document->documentStatus) }}</p>
        </div>
        <div>
          <p class="tw-font-semibold">Document Created Date:</p>
          <p class="tw-text-gray-700">{{ $document->created_at->setTimezone('Asia/Manila')->format('M d, Y') }}</p>
        </div>
        <div>
          <p class="tw-font-semibold">Document Created Time:</p>
          <p class="tw-text-gray-700">{{ $document->created_at->setTimezone('Asia/Manila')->format('h:i A') }}</p>
        </div>
      </div>
      <div>
        <div class="tw-flex tw-items-center tw-justify-start tw-mt-6">
          <a href="{{ route('vendorPortal.order.document') }}" class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-bg-white tw-text-gray-700 tw-font-semibold tw-text-sm tw-cursor-pointer">
            <i class="fa-solid fa-arrow-left tw-mr-2"></i>
            Back
          </a>
        </div>
      </div>
    </div>
    <hr>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2">Order Table Instructions</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2">
        <p class="tw-mb-1">In the table above, you can view and manage your order requests created by you by clicking on the "Order No." column of the table. When you click on the "Order No.", you will be redirected to the order details page. </p>
        <p class="tw-mt-2">To add a new order request, click the "Add New" button.</p>
      </div>
    </div>
  </div>

</x-layout.portal.mainTemplate>