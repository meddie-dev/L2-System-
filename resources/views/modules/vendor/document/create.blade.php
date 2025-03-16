<x-layout.portal.mainTemplate>
  <nav class="tw-flex | max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : (Auth::user()->hasRole('Vendor') ? 'vendorPortal.dashboard' : 'staff.dashboard'))) }}"  :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('vendorPortal.order') }}" :active="false" :isLast="false">
        Order Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        (Step 2) Upload New Documents
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="tw-px-4">
    <p class="tw-text-sm tw-text-gray-500 | max-md:tw-text-xs"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>
    <form action="{{ route('vendorPortal.order.document.store', ['order' => $order->id]) }}" enctype="multipart/form-data" method="POST" class="tw-mt-6">
      @csrf
      <div class="tw-text-sm">
        <!-- Document Number -->
        <div class="tw-mb-4">
          <label for="documentNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Document Number<span class="tw-text-red-500">*</span></label>
          <input type="text" id="documentNumber" name="documentNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('documentNumber') is-invalid @enderror" placeholder="ENTER ORDER NUMBER" value="{{ strtoupper(Str::random(20)) }}" readonly>
          @error('documentNumber')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Document Name -->
        <div class="tw-mb-4">
          <label for="documentName" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs ">Document Name/Description<span class="tw-text-red-500">*</span></label>
          <input type="text" id="documentName" name="documentName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('documentName') is-invalid @enderror" placeholder="Bill of Lading (BOL)" required>
          @error('documentName')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>
        <!-- Document Upload -->
        <div class="tw-mb-4">
          <label for="documentUrl" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">
            Document Upload <span class="tw-text-red-500">*</span>
          </label>
          <div>
            <input type="file" id="documentUrl" name="documentUrl" required class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-sm-text-sm | max-md:tw-text-xs @error('documentUrl') is-invalid @enderror">
          </div>
          @error('documentUrl')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>
      </div>

      <!-- Submit Registration Button -->
      <div class="tw-my-6 tw-flex tw-justify-end | max-md:tw-my-2">
        <button type="submit" id="submitBtn" class=" tw-bg-indigo-600 tw-text-white tw-px-6 tw-py-2 tw-mb-2 tw-rounded-md tw-shadow-md hover:tw-bg-indigo-700 | max-md:tw-text-sm">Proceed</button>
      </div>
    </form>
    <hr>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2 | max-md:tw-text-sm">Review After Submission</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2 | max-md:tw-text-[11px]">
        <p class="tw-mb-1">Once the order request is submitted, you will receive an email notification. Please check your email for further instructions. The Staff will review your order request and provide you with a response within 24 hours or as soon as possible.</p>
      </div>
    </div>
  </div>
</x-layout.portal.mainTemplate>