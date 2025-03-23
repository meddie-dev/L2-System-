<x-layout.portal.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-sm:justify-center" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route('vendorPortal.dashboard') }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="false">
        <div class="tw-flex tw-items-center tw-justify-center">
          Vehicle Reservation
        </div>
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Reservation (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $tripTicket->tripNumber }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    @php
    $steps = ['Scheduled', 'In Transit', 'Delivered'];
    $currentStep = match ($tripTicket->status) {
    'scheduled' => 'Scheduled',
    'in_transit' => 'In Transit',
    'delivered' => 'Delivered',
    default => 'Unknown',
    };
    @endphp

    <!-- Progress Bar -->
    <div class="tw-p-6">
      <div class="tw-flex tw-items-center tw-justify-center tw-w-full">
        @foreach ($steps as $index => $step)
        <div class="relative tw-flex tw-flex-col tw-items-center">
          <!-- Step Circle -->
          <div class="tw-w-10 tw-h-10 tw-flex tw-items-center tw-justify-center tw-rounded-full 
                {{ $step === $currentStep ? 'tw-bg-yellow-500 tw-text-white' : ($index < array_search($currentStep, $steps) ? 'tw-bg-teal-500 tw-text-white' : 'tw-bg-gray-300 tw-text-gray-500') }}">
            @if ($step == 'Scheduled')
            <i class="fas fa-calendar-check"></i>
            @elseif ($step == 'In Transit')
            <i class="fas fa-truck"></i>
            @else
            <i class="fas fa-check"></i>
            @endif
          </div>

          <!-- Step Text -->
          <span class="tw-text-sm tw-mt-2 {{ $step === $currentStep ? 'tw-text-yellow-500' : 'tw-text-gray-600' }}">{{ $step }}</span>
        </div>

        @if (!$loop->last)
        <!-- Connecting Line -->
        <div class="tw-flex-1 tw-h-1 tw-mb-6 {{ $index < array_search($currentStep, $steps) ? 'tw-bg-teal-500' : 'tw-bg-gray-300' }}"></div>
        @endif
        @endforeach
      </div>
    </div>

    <div class="tw-overflow-x-auto tw-mb-6">

      @if($tripTicket->status === 'in_transit' || $tripTicket->status === 'delivered')
      <!-- Driver Details -->
      <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Driver Information</h3>
      <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-gap-2">
        <div>
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Name</label>
          <input type="text" id="approvedByName" name="approvedByName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="APPROVED BY NAME" value="{{ optional(App\Models\User::find($tripTicket->user_id))->firstName }} {{ optional(App\Models\User::find($tripTicket->user_id))->lastName }}" readonly>
        </div>
        <div>
          <label for="product" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Driver Type</label>
          <input type="text" id="product" name="product" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ (\App\Models\User::find($tripTicket->user_id))->driverType === 'light' ? 'Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)' : ((\App\Models\User::find($tripTicket->user_id))->driverType === 'medium' ? 'Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)' : ((\App\Models\User::find($tripTicket->user_id))->driverType === 'heavy' ? 'Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)' : 'N/A')) }}" readonly>
        </div>
        <div>
          <label for="deliveryLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Role</label>
          <input type="text" id="deliveryLocation" name="deliveryLocation" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ optional(App\Models\User::find($tripTicket->user_id))->roles->first()->name ?? 'N/A' }}" readonly>
        </div>
      </div>

      <!-- Delivery Details -->
      <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Delivery Details</h3>
      <div class="tw-grid tw-grid-cols-3 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-grid-cols-1 max-md:tw-text-xs max-md:tw-gap-2">
        <div>
          <label for="tripNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Trip Number</label>
          <input type="text" id="tripNumber" name="tripNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $tripTicket->tripNumber }}" readonly>
        </div>
        <div>
          <label for="destination" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Destination</label>
          <input type="text" id="destination" name="destination" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $tripTicket->destination }}" readonly>
        </div>
        <div>
          <label for="departureDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Departure Date</label>
          <input type="text" id="departureDate" name="departureDate" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ \Carbon\Carbon::parse($tripTicket->departureTime)->format('F j, Y') }}" readonly>
        </div>
        <div>
          <label for="departureTime" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Departure Time</label>
          <input type="text" id="departureTime" name="departureTime" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ \Carbon\Carbon::parse($tripTicket->departureTime)->format('g:i A') }}" readonly>
        </div>
        <div>
          <label for="arrivalDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Arrival Date</label>
          <input type="text" id="arrivalDate" name="arrivalDate" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ \Carbon\Carbon::parse($tripTicket->arrivalTime)->format('F j, Y') }}" readonly>
        </div>
        <div>
          <label for="arrivalTime" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Arrival Time</label>
          <input type="text" id="arrivalTime" name="arrivalTime" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ \Carbon\Carbon::parse($tripTicket->arrivalTime)->format('g:i A') }}" readonly>
        </div>
        <div>
          @php
          $totalMinutes = $tripTicket->duration;
          $hours = floor($totalMinutes / 60);
          $minutes = floor($totalMinutes % 60);
          $seconds = round(($totalMinutes - floor($totalMinutes)) * 60);
          @endphp
          <label for="duration" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Duration</label>
          <input type="text" id="duration" name="duration" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ sprintf('%d hour%s %d minute%s %d second%s', $hours, $hours == 1 ? '' : 's', $minutes, $minutes == 1 ? '' : 's', $seconds, $seconds == 1 ? '' : 's') }}" readonly>
        </div>
      </div>
      @endif

      @if($tripTicket->rating !== null)
        <!-- Rate and Feedback Details -->
      <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Feedback and Rating</h3>
      <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-gap-2">
        <div>
          <label for="rating" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Rating</label>
          @php
            $ratingText = match($tripTicket->rating) {
              1 => '1 - Very Poor',
              2 => '2 - Poor',
              3 => '3 - Average',
              4 => '4 - Good',
              5 => '5 - Excellent',
              default => 'N/A',
            };
          @endphp
          <input type="text" id="rating" name="rating" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $ratingText }}" readonly>
        </div>
        <div>
          <label for="feedback" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Feedback</label>
          <textarea id="feedback" name="feedback" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" rows="5" readonly>{{ $tripTicket->feedback }}</textarea>
        </div>
      </div>
      @endif

      <!-- Review By -->
      <h3 class="tw-text-md tw-font-bold tw-my-4 | max-md:tw-text-sm">Approved By</h3>
      <div class="tw-grid tw-grid-cols-1 tw-gap-4 tw-px-4 tw-text-sm | max-md:tw-text-xs max-md:tw-gap-2">
        <div>
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Name</label>
          <input type="text" id="approvedByName" name="approvedByName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" placeholder="APPROVED BY NAME" value="{{ optional(App\Models\User::find($tripTicket->approved_by))->firstName }} {{ optional(App\Models\User::find($tripTicket->approved_by))->lastName }}" readonly>
        </div>
        <div>
          <label for="product" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Reviewed At</label>
          <input type="text" id="product" name="product" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ $tripTicket->updated_at->format('Y-m-d') }}" readonly>
        </div>
        <div>
          <label for="deliveryLocation" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Role</label>
          <input type="text" id="deliveryLocation" name="deliveryLocation" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs" value="{{ optional(App\Models\User::find($tripTicket->approved_by))->roles->first()->name ?? 'N/A' }}" readonly>
        </div>
      </div>
    <div class="tw-flex tw-justify-between">
      <div class="tw-flex tw-items-center tw-justify-start tw-my-6">
        <a href="{{ route('vendorPortal.order') }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-p-3 ">
          <i class="fa-solid fa-arrow-left tw-mr-2 | max-md:tw-text-xs"></i>
          Back
        </a>
      </div>
      @if($tripTicket->status === 'delivered' && $tripTicket->rating === null)
      <div class="tw-flex tw-items-center tw-justify-start tw-my-6">
        <a href="{{ route('vendorPortal.card.makeRate',$tripTicket->id) }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-p-3 ">
          Mark as Delivered
        </a>
      </div>
      @endif
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