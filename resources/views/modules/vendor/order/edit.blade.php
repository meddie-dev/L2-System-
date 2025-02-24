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
          Edit Order Request (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $order->orderNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="tw-px-4">
    <p class="tw-text-sm tw-text-gray-500"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>
    <form action="{{ route('vendorPortal.order.update', $order->id) }}" enctype="multipart/form-data" method="POST" class="tw-mt-6">
      @csrf
      @method('PATCH')
      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-text-sm | max-sm:tw-grid-cols-1 max-sm:tw-text-[13px] ">
        <!-- Order Number -->
        <div class="tw-mb-4">
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500">Order Number<span class="tw-text-red-500">*</span></label>
          <input type="text" id="orderNumber" name="orderNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed" placeholder="ENTER ORDER NUMBER" value="{{ $order->orderNumber }}" readonly>
          @error('orderNumber')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Product -->
        <div class="tw-mb-4">
          <label for="product" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Product<span class="tw-text-red-500">*</span></label>
          <input type="text" id="product" name="product" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500" value="{{ $order->product }}" placeholder="Enter product" required>
          @error('product')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Quantity -->
        <div class="tw-mb-4">
          <label for="quantity" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Quantity<span class="tw-text-red-500">*</span></label>
          <input type="number" id="quantity" name="quantity" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500" value="{{ $order->quantity }}" placeholder="Enter quantity" required max="255">
          @error('quantity')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Weight -->
        <div class="tw-mb-4">
          <label for="weight" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Weight (Kg)<span class="tw-text-red-500">*</span></label>
          <input type="number" id="weight" name="weight" step="0.01" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500" value="{{ $order->weight }}" placeholder="Enter weight" required>
          @error('weight')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>


        <!-- Delivery Address -->
        <div class="tw-mb-4">
          <label for="deliveryAddress" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Delivery Address<span class="tw-text-red-500">*</span></label>
          <input type="text" id="deliveryAddress" name="deliveryAddress" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500" value="{{ $order->deliveryAddress }}" placeholder="Enter delivery address" required>
          @error('deliveryAddress')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Delivery Request Date -->
        <div class="tw-mb-4">
          <label for="deliveryRequestDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Delivery Request Date<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <input type="date" id="deliveryRequestDate" name="deliveryRequestDate" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500" value="{{ $order->deliveryRequestDate }}" placeholder="Enter delivery request date" required>
          </div>
          @error('deliveryRequestDate')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Special Instructions -->
        <div class="tw-mb-4">
          <label for="specialInstructions" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Special Instructions (Optional)</label>
          <input type="text" id="specialInstructions" name="specialInstructions" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500" value="{{ $order->specialInstructions ?? '' }}" placeholder="Enter any special instructions">
          @error('specialInstructions')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>
      </div>

      <!-- Submit Registration Button -->
      <div class="tw-my-6 tw-flex tw-justify-end">
        <button type="submit" class=" tw-bg-indigo-600 tw-text-white tw-px-6 tw-py-2 tw-mb-2 tw-rounded-md tw-shadow-md hover:tw-bg-indigo-700">Proceed</button>
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

  <script>
    const inputElement = document.getElementById("total_amount");
    inputElement.addEventListener("input", function(e) {
      let value = e.target.value;
      value = value.replace(/[^\d]/g, "");
      value = parseInt(value).toLocaleString("en-US");
      e.target.value = value;
    });
  </script>
</x-layout.portal.mainTemplate>