<x-layout.portal.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-sm:justify-center" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route('vendorPortal.dashboard') }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="false">
        <div class="tw-flex tw-items-center tw-justify-center">
          Make Rate/Feedback
        </div>
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Delivery (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $tripTicket->tripNumber }}</span>)
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
      <!-- Order Information -->
      <h3 class="tw-text-md tw-font-bold tw-my-4">Rate and Feedback</h3>
      <form action="{{ route('tripTicket.rate', $tripTicket->id) }}" method="POST">
        @csrf
        <div class="tw-mb-3">
          <label for="rating" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Rating (1-5)</label>
          <select name="rating" id="rating" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 | max-md:tw-text-xs" required>
            <option value="">Select a Rating</option>
            <option value="1">1 - Very Poor</option>
            <option value="2">2 - Poor</option>
            <option value="3">3 - Average</option>
            <option value="4">4 - Good</option>
            <option value="5">5 - Excellent</option>
          </select>
        </div>
        <div class="tw-mb-3">
          <label for="feedback" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Feedback</label>
          <textarea name="feedback" id="feedback" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 | max-md:tw-text-xs" rows="4" placeholder="Write your feedback here..."></textarea>
        </div>
        <button type="submit" class="tw-bg-blue-500 hover:tw-bg-blue-600 tw-text-white tw-font-bold tw-py-2 tw-px-4 tw-rounded tw-opacity-50 tw-float-right">Submit Rating</button>
      </form>
    </div>

    <div class="tw-flex tw-items-center tw-justify-start tw-my-6">
      <a href="{{ route('vendorPortal.card.details', $tripTicket->id) }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-p-3 ">
        <i class="fa-solid fa-arrow-left tw-mr-2 | max-md:tw-text-xs"></i>
        Back
      </a>
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