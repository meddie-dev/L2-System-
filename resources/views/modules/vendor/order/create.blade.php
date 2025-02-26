<x-layout.portal.mainTemplate>
  <nav class="tw-flex | max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('vendorPortal.order') }}" :active="false" :isLast="false">
        Order Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        (Step 1) Add New Order Request
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="tw-px-4"> 
    <p class="tw-text-sm tw-text-gray-500 | max-md:tw-text-xs"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>
    <form id="orderForm" action="{{ route('vendorPortal.order.store',  ['user' => auth()->id()]) }}" enctype="multipart/form-data" method="POST"  class="tw-mt-6">
      @csrf
      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-text-sm | max-md:tw-grid-cols-1 max-md:tw-gap-2 ">
        <!-- Order Number -->
        <div class="tw-mb-4">
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Order Number<span class="tw-text-red-500">*</span></label>
          <input type="text" id="orderNumber" name="orderNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('orderNumber') is-invalid @enderror" placeholder="ENTER ORDER NUMBER" value="{{ strtoupper(Str::random(20)) }}" readonly>
          @error('orderNumber')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Product -->
        <div class="tw-mb-4">
          <label for="product" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Product<span class="tw-text-red-500">*</span></label>
          <input type="text" id="product" name="product" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('product') is-invalid @enderror" placeholder="Enter product" required>
          @error('product')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Quantity -->
        <div class="tw-mb-4">
          <label for="quantity" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Quantity<span class="tw-text-red-500">*</span></label>
          <input type="number" id="quantity" name="quantity" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('quantity') is-invalid @enderror" placeholder="Enter quantity" required min="1">
          @error('quantity')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Weight -->
        <div class="tw-mb-4">
          <label for="weight" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Weight (Kg)<span class="tw-text-red-500">*</span></label>
          <input type="number" id="weight" name="weight" step="0.01" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('weight') is-invalid @enderror" placeholder="Enter weight" required>
          @error('weight')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>
        

        <!-- Delivery Address -->
        <div class="tw-mb-4">
          <label for="deliveryAddress" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Delivery Address<span class="tw-text-red-500">*</span></label>
          <input type="text" id="deliveryAddress" name="deliveryAddress" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('deliveryAddress') is-invalid @enderror" placeholder="Enter delivery address" required>
          @error('deliveryAddress')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Delivery Request Date -->
        <div class="tw-mb-4">
          <label for="deliveryRequestDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Delivery Request Date<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <input type="date" id="deliveryRequestDate" name="deliveryRequestDate" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('deliveryRequestDate') is-invalid @enderror" placeholder="Enter delivery request date" required>
          </div>
          @error('deliveryRequestDate')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Special Instructions -->
        <div class="tw-mb-4">
          <label for="specialInstructions" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Special Instructions (Optional)</label>
          <input type="text" id="specialInstructions" name="specialInstructions" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('specialInstructions') is-invalid @enderror" placeholder="Enter any special instructions">
          @error('specialInstructions')
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