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
          Review Document No.(<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $order->document->first()->documentNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-overflow-x-auto tw-mb-6">
      <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Vendor Information</h3>
      <div class="tw-px-4 tw-flex tw-flex-col tw-gap-2 tw-text-sm | max-md:tw-text-xs">
        <div>
          <p class="tw-font-semibold">Vendor ID: (<span class="tw-text-gray-700 tw-font-normal">{{ $order->user ? $order->user->id : 'N/A' }}</span>)</p>
        </div>
        <div>
          <p class="tw-font-semibold ">Vendor Name: (<span class="tw-text-gray-700 tw-font-normal">{{ $order->user ? $order->user->firstName . ' ' . $order->user->lastName : 'N/A' }}</span>)</p>
        </div>
        <div>
          <p class="tw-font-semibold">Email: (<span class="tw-text-gray-700 tw-font-normal">{{ $order->user ? $order->user->email : 'N/A' }}</span>)</p>
        </div>
        <div>
          <p class=" tw-font-semibold">Verification Status: (<span class="tw-text-gray-700 tw-font-normal">{{ $order->user ? ($order->user->two_factor_enabled ? 'Verified' : 'Not Verified') : 'N/A' }}</span>)</p>
        </div>
      </div>
      <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Order Information</h3>
      <div class="tw-flex tw-flex-col tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-grid-cols-1 max-md:tw-gap-2">
        <div>
          <p class=" tw-font-semibold">Order Number: (<span class="tw-text-gray-700 tw-font-normal">{{ $order->orderNumber}}</span>)</p>
          </p>
        </div>
      </div>
      <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Document Submissions</h3>
      <div class="tw-flex tw-flex-col tw-gap-2 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-grid-cols-1 max-md:tw-gap-2">
        <div class="tw-flex tw-items-center tw-justify-center tw-relative tw-mb-4">
          <img src="{{ asset('storage/' . $order->document->first()->documentUrl) }}" alt="Document Image" class="tw-max-w-[500px]  tw-max-h-[500px] tw-rounded-md tw-shadow-md tw-object-fill">
          <div style="font-size: 2rem; left: 50%; top: 50%; transform: translate(-50%, -50%);" class="tw-absolute tw-z-10 tw-text-gray-700 tw-opacity-10 tw-font-bold">
            {{ $order->document->first()->documentNumber ?? 'N/A' }}
          </div>
        </div>

        <div class="tw-grid tw-grid-cols-2 tw-gap-2">
          <div>
            <p class=" tw-font-semibold">Document Number: </p>
            <p class="tw-text-gray-700">{{ $order->document->first()->documentNumber }}</p>
          </div>
          <div>
            <p class=" tw-font-semibold">Document Name:</p>
            <p class="tw-text-gray-700">{{ $order->document->first()->documentName }}</p>
          </div>
          <div>
            <p class=" tw-font-semibold">Document Status:</p>
            <p class="tw-text-gray-700">{{ ucfirst($order->document->first()->approval_status) }}</p>
          </div>
          <div>
            <p class=" tw-font-semibold">Document Create:</p>
            <p class="tw-text-gray-700">{{ $order->document->first()->created_at->format('Y-m-d') }}</p>
          </div>

        </div>
      </div>

    </div>
    <div class="tw-flex tw-justify-end tw-mb-6">
      <form action="{{ route('staff.document.approve', $order->document->first()->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="tw-text-white tw-bg-gray-700 tw-text-sm tw-font-bold tw-py-2 tw-px-4 tw-rounded | max-md:tw-text-xs">Approve Document</button>
      </form>
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