<x-layout.portal.mainTemplate>
  <nav class="tw-flex | max-sm:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : (Auth::user()->hasRole('Vendor') ? 'vendorPortal.dashboard' : 'staff.dashboard'))) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('vendorPortal.order') }}" :active="false" :isLast="false">
        Order Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Edit Payment (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $order->orderNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="tw-px-4">
    <p class="tw-text-sm tw-text-gray-500"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>
    <form action="{{ route('vendorPortal.order.payment.update', $order->id) }}" enctype="multipart/form-data" method="POST" class="tw-mt-6">
      @csrf
      @method('PATCH')
      <div class="tw-text-sm">
        <!-- Payment Number -->
        <div class="tw-mb-4">
          <label for="paymentNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Payment Number<span class="tw-text-red-500">*</span></label>
          <input type="text" id="paymentNumber" name="paymentNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('paymentNumber') is-invalid @enderror" placeholder="ENTER ORDER NUMBER" value="{{ $order->payment->paymentNumber }}" readonly>
          @error('paymentNumber')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Date -->
        <div class="tw-mb-4">
          <label for="date" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Date<span class="tw-text-red-500">*</span></label>
          <input type="date" id="date" name="date" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm | max-md:tw-text-xs @error('date') is-invalid @enderror" value="{{ old('date', $order->payment->date) }}" required>
          @error('date')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Due Date -->
        <div class="tw-mb-4">
          <label for="due_date" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Due Date<span class="tw-text-red-500">*</span></label>
          <input type="date" id="due_date" name="due_date" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('due_date') is-invalid @enderror" value="{{ old('due_date', date('Y-m-d', strtotime('+1 month', strtotime(request()->input('date', date('Y-m-d')))))) }}" readonly>
          @error('due_date')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Account Number -->
        <div class="tw-mb-4">
          <label for="account_number" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Account Number<span class="tw-text-red-500">*</span></label>
          <input type="text" id="account_number" name="account_number" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm | max-md:tw-text-xs @error('account_number') is-invalid @enderror" placeholder="Enter Account Number" min="17" max="17" value="{{  $order->payment->account_number }}" required>
          @error('account_number')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Total Amount Due -->
        <div class="tw-mb-2">
          <label for="total_amount_due" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Total Amount Due<span class="tw-text-red-500">*</span></label>
          <input type="number" id="total_amount_due" name="total_amount_due" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed  | max-md:tw-text-xs @error('total_amount_due') is-invalid @enderror" value="{{ old('total_amount_due', $order->payment->total_amount_due) }}" readonly>
          @error('total_amount_due')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

      </div>

      <!-- Submit Registration Button -->
      <div class="tw-my-6 tw-flex tw-justify-end">
        <button type="submit" class=" tw-bg-indigo-600 tw-text-white tw-px-6 tw-py-2 tw-mb-2 tw-rounded-md tw-shadow-md hover:tw-bg-indigo-700">Submit</button>
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

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const dateInput = document.getElementById("date");
      const dueDateInput = document.getElementById("due_date");

      function updateDueDate() {
        let selectedDate = new Date(dateInput.value);
        if (!isNaN(selectedDate.getTime())) {
          selectedDate.setMonth(selectedDate.getMonth() + 1);
          let formattedDate = selectedDate.toISOString().split("T")[0];
          dueDateInput.value = formattedDate;
        }
      }

      dateInput.addEventListener("change", updateDueDate);
      updateDueDate(); // Initial call to set the default value
    });
  </script>
</x-layout.portal.mainTemplate>