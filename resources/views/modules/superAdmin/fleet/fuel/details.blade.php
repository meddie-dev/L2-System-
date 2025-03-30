<x-layout.mainTemplate>
  <nav class="tw-flex | max-sm:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb  :active="true" :isLast="false">
        Fleet Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Fuel Number (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $fuel->fuelNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div>
    <!-- Driver Details -->
    <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
      <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Fleet Card</h2>
      <p class="tw-text-xs | max-md:tw-text-xs">Edit and update your driver's information.</p>
    </div>
    <div class="tw-px-4">
      <p class="tw-text-sm tw-text-gray-500"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>
      <form action="{{ route('superadmin.fleet.fuel.update', $fuel->id) }}" enctype="multipart/form-data" method="POST" class="tw-mt-6">
        @csrf
        @method('PATCH')
        <div class="tw-grid tw-grid-cols-2 tw-gap-3 tw-text-sm | max-md:tw-grid-cols-1 max-md:tw-gap-2 ">
          <!-- Card Number -->
          <div class="tw-mb-4">
            <label for="cardNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Card Number<span class="tw-text-red-500">*</span></label>
            <input type="text" id="cardNumber" name="cardNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('cardNumber') is-invalid @enderror" placeholder="Enter card number" value="{{ $fuel->fleetCard->cardNumber }}" readonly>
            @error('cardNumber')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Credit Limit -->
          <div class="tw-mb-4">
            <label for="credit_limit" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Credit Limit<span class="tw-text-red-500">*</span></label>
            <input type="number" id="credit_limit" name="credit_limit" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('credit_limit') is-invalid @enderror" placeholder="Enter credit limit" value="{{ $fuel->fleetCard->credit_limit }}" required>
            @error('credit_limit')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Balance -->
          <div class="tw-mb-4">
            <label for="balance" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Balance<span class="tw-text-red-500">*</span></label>
            <input type="number" id="balance" name="balance" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('balance') is-invalid @enderror" placeholder="Enter balance" value="{{ $fuel->fleetCard->balance }}" >
            @error('balance')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Status -->
          <div class="tw-mb-4">
            <label for="status" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Status<span class="tw-text-red-500">*</span></label>
            <select id="status" name="status" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('status') is-invalid @enderror" required>
              <option value="active" {{ $fuel->status === 'active' ? 'selected' : '' }}>Active</option>
              <option value="inactive" {{ $fuel->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Expiry Date -->
          <div class="tw-mb-4">
            <label for="expiry_date" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Expiry Date<span class="tw-text-red-500">*</span></label>
            <input type="date" id="expiry_date" name="expiry_date" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('expiry_date') is-invalid @enderror" placeholder="Enter expiry date" value="{{ $fuel->expiry_date }}" >
            @error('expiry_date')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Created At -->
          <div class="tw-mb-4">
            <label for="created_at" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Created At<span class="tw-text-red-500">*</span></label>
            <input type="datetime-local" id="created_at" name="created_at" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('created_at') is-invalid @enderror" placeholder="Enter created at" value="{{ $fuel->created_at }}" readonly>
            @error('created_at')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

        </div>
        <!-- Submit Registration Button -->
        <div class="tw-flex tw-justify-end">
          <button type="submit" class=" tw-bg-indigo-600 tw-text-white tw-px-6 tw-py-2 tw-mb-2 tw-rounded-md tw-shadow-md hover:tw-bg-indigo-700">Update</button>
        </div>
      </form>
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