<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between |  max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'driver.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('driver.card') }}" :active="false" :isLast="false">
        Card Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Review Card (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $fleetCard->cardNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Fleet Card</h3>
    <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-px-4 tw-py-4 tw-text-sm | max-md:tw-text-xs max-md:tw-grid-cols-1 max-md:tw-gap-2">
      <!-- Card Number -->
      <div>
        <label for="cardNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Card Number:</label>
        <input type="text" id="cardNumber" name="cardNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ $fleetCard->cardNumber }}" readonly>
      </div>

      <!-- Credit Limit -->
      <div>
        <label for="creditLimit" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Credit Limit:</label>
        <input type="text" id="creditLimit" name="creditLimit" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="PHP {{ number_format($fleetCard->credit_limit, 2) }}" readonly>
      </div>

      <!-- Balance -->
      <div>
        <label for="balance" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Balance:</label>
        <input type="text" id="balance" name="balance" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="PHP {{ number_format($fleetCard->balance, 2) }}" readonly>
      </div>

      <!-- Status -->
      <div>
        <label for="status" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Status:</label>
        <input type="text" id="status" name="status" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ ucfirst($fleetCard->status) }}" readonly>
      </div>

      <!-- Expiry Date -->
      <div>
        <label for="expiryDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Expiry Date:</label>
        <input type="text" id="expiryDate" name="expiryDate" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ $fleetCard->expiry_date ? \Carbon\Carbon::parse($fleetCard->expiry_date)->format('F j, Y') : '-' }}" readonly>
      </div>

      <!-- Created At -->
      <div>
        <label for="createdAt" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Created At:</label>
        <input type="text" id="createdAt" name="createdAt" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="ENTER ORDER NUMBER" value="{{ \Carbon\Carbon::parse($fleetCard->createdAt)->format('F j, Y') }}" readonly>
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
</x-layout.mainTemplate>