<x-layout.portal.mainTemplate>
  <nav class="tw-flex | max-sm:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('vendorPortal.order') }}" :active="false" :isLast="false">
        Order Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Edit Order Request (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $vendor->orderNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="tw-px-4">
    <p class="tw-text-sm tw-text-gray-500"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>
    <form action="{{ route('vendorPortal.order.update', $vendor->id) }}" enctype="multipart/form-data" method="POST" class="tw-mt-6">
      @csrf
      @method('PATCH')
      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-text-sm | max-sm:tw-grid-cols-1 max-sm:tw-text-[13px] ">
        <!-- Order Number -->
        <div class="tw-mb-4 ">
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-sm:tw-text-[13px]">Order Number<span class="tw-text-red-500">*</span></label>
          <input type="text" id="orderNumber" name="orderNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed" placeholder="ENTER ORDER NUMBER" value="{{ $vendor->orderNumber }}" readonly>
          @error('orderNumber')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Pickup Location -->
        <div class="tw-mb-4">
          <label for="pickupLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 |  max-sm:tw-text-[13px]">Pickup Location<span class="tw-text-red-500">*</span></label>
          <input type="text" id="pickupLocation" name="pickupLocation" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500" value="{{ $vendor->pickupLocation }}" placeholder="Enter pickup location" required>
          @error('pickupLocation')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Delivery Location -->
        <div class="tw-mb-4">
          <label for="deliveryLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 |  max-sm:tw-text-[13px]">Delivery Location<span class="tw-text-red-500">*</span></label>
          <input type="text" id="deliveryLocation" name="deliveryLocation" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500" value="{{ $vendor->deliveryLocation }}" placeholder="Enter delivery location" required>
          @error('deliveryLocation')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Delivery Deadline -->
        <div class="tw-mb-4">
          <label for="deliveryDeadline" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 |  max-sm:tw-text-[13px]">Delivery Deadline<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <input type="date" id="deliveryDeadline" name="deliveryDeadline" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500" value="{{ $vendor->deliveryDeadline }}" placeholder="Enter delivery deadline" required>
          </div>
          @error('deliveryDeadline')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Package Weight -->
        <div class="tw-mb-4">
          <label for="packageWeight" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 |  max-sm:tw-text-[13px]">Package Weight (kg)<span class="tw-text-red-500">*</span></label>
          <input type="number" step="0.01" id="packageWeight" name="packageWeight" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500" value="{{ $vendor->packageWeight }}" placeholder="Enter package weight in kilograms (kg)" required min="0">
          @error('packageWeight')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Special Instructions -->
        <div class="tw-mb-4">
          <label for="specialInstructions" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 |  max-sm:tw-text-[13px]">Special Instructions (Optional)</label>
          <input type="text" id="specialInstructions" name="specialInstructions" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500" value="{{ $vendor->specialInstructions }}" placeholder="Enter any special instructions">
          @error('specialInstructions')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Document Upload -->
        <div class="tw-mb-4">
          <label for="documentUpload" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 |  max-sm:tw-text-[13px]">Upload Documents (Optional)</label>
          <p class="tw-text-xs tw-text-gray-500 tw-mb-1">Upload any relevant documents.</p>
          <div class="tw-mt-1 tw-flex tw-justify-center tw-p-2 tw-border tw-border-gray-300 tw-rounded-md">
            <input type="file" id="documentUpload" name="documentUpload" class="tw-block tw-w-full" value="{{ $vendor->documentUpload }}" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
          </div>
          @error('documentUpload')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>
      </div>

      <!-- Submit Registration Button -->
      <div class="tw-my-6 tw-text-center">
        <button type="submit" class="tw-w-full tw-bg-gray-600 tw-text-white tw-px-4 tw-py-2 tw-mb-2 tw-rounded-md tw-shadow-md hover:tw-bg-gray-700 | max-sm:tw-text-sm">Update Order Request</button>
        <a href="{{ route('vendorPortal.order') }}" class="tw-text-sm tw-font-medium tw-text-center tw-text-gray-700 | max-sm:tw-text-sm">Go Back</a>
      </div>
    </form>
    <hr>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2">Review After Submission</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2">
        <p class="tw-mb-1">Once the order request is submitted, you will receive an email notification. Please check your email for further instructions. The Staff will review your order request and provide you with a response within 24 hours or as soon as possible.</p>
      </div>
    </div>
  </div>


</x-layout.portal.mainTemplate>