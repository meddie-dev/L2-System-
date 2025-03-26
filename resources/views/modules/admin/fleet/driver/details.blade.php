<x-layout.mainTemplate>
  <nav class="tw-flex | max-sm:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('admin.fleet.index') }}" :active="false" :isLast="false">
        Manage Driver
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        <div class="tw-flex tw-items-center tw-justify-center ">
          Driver (<span class="tw-font-semibold tw-text-xs tw-opacity-20 tw-mt-1 ">{{ $user->firstName }} {{ $user->lastName }}</span>)
        </div>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div>
    <!-- Driver Details -->
    <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
      <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Driver Details</h2>
      <p class="tw-text-xs | max-md:tw-text-xs">Edit and update your driver's information.</p>
    </div>
    <div class="tw-px-4">
      <p class="tw-text-sm tw-text-gray-500"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>
      <form action="{{ route('admin.fleet.driver.update', $user->id) }}" enctype="multipart/form-data" method="POST" class="tw-mt-6">
        @csrf
        @method('PATCH')
        <div class="tw-grid tw-grid-cols-2 tw-gap-3 tw-text-sm | max-md:tw-grid-cols-1 max-md:tw-gap-2 ">
          <!-- First Name -->
          <div class="tw-mb-4">
            <label for="firstName" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">First Name<span class="tw-text-red-500">*</span></label>
            <input type="text" id="firstName" name="firstName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('firstName') is-invalid @enderror" placeholder="Enter first name" value="{{ $user->firstName }}" required>
            @error('firstName')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Last Name -->
          <div class="tw-mb-4">
            <label for="lastName" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Last Name<span class="tw-text-red-500">*</span></label>
            <input type="text" id="lastName" name="lastName" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('lastName') is-invalid @enderror" placeholder="Enter last name" value="{{ $user->lastName }}" required>
            @error('lastName')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Email -->
          <div class="tw-mb-4">
            <label for="email" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Email Address<span class="tw-text-red-500">*</span></label>
            <input type="email" id="email" name="email" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('email') is-invalid @enderror" placeholder="Enter email address" value="{{ $user->email }}" required>
            @error('email')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Driver Type -->
          <div class="tw-mb-4">
            <label for="driverType" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Driver Type<span class="tw-text-red-500">*</span></label>
            <select id="driverType" name="driverType" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('driverType') is-invalid @enderror" required>
              <option value="light" {{ $user->driverType === 'light' ? 'selected' : '' }}>Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)</option>
              <option value="medium" {{ $user->driverType === 'medium' ? 'selected' : '' }}>Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)</option>
              <option value="heavy" {{ $user->driverType === 'heavy' ? 'selected' : '' }}>Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)</option>
            </select>
            @error('driverType')
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


    <!-- Performance Tracking -->
    <div class="card-body">
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Performance Tracking</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">Track the performance of your drivers and vehicles.</p>
      </div>
      <div class="tw-px-4">
        <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4">
          <thead class="tw-bg-gray-200 tw-text-gray-700">
            <tr>
              <th class="tw-px-4 tw-py-2">Month</th>
              <th class="tw-px-4 tw-py-2">Performance Score</th>
              <th class="tw-px-4 tw-py-2">Average Rating</th>
              <th class="tw-px-4 tw-py-2">On Time Deliveries</th>
              <th class="tw-px-4 tw-py-2">Late Deliveries</th>
              <th class="tw-px-4 tw-py-2">Early Deliveries</th>
            </tr>
          </thead>
          <tbody id="performanceRecords" class="tw-bg-white">

            <!-- Display Month as a Full-Row Header -->
            <tr class="tw-bg-gray-100">
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{ date('F Y') }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{$user->performance_score }}</td>
              <td class="tw-px-4 tw-py-2">
                <span class="tw-text-sm tw-font-bold tw-inline-block tw-text-gray-500">
                  @for ($i = 0; $i < 5; $i++)
                    @if ($i < floor($user->tripTickets()->avg('rating')))
                    <span style="color: #3490dc;">&#9733;</span>
                    @else
                    <span style="color: #6c757d;">&#9734;</span>
                    @endif
                    @endfor
                </span>
                ({{ number_format($user->tripTickets()->avg('rating'), 1) }}/5
                )
              </td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{$user->on_time_deliveries }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{$user->late_deliveries }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{$user->early_deliveries }}</td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>


    <!-- Trip Tracking -->
    <div class="card-body">
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Trip Tracking</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">Ensure that all trip data are accurate and up-to-date for efficient tracking and reporting.</p>
      </div>
      <div class="tw-px-4">
        <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-mt-4">
          <thead class="tw-bg-gray-200 tw-text-gray-700">
            <tr>
              <th class="tw-px-4 tw-py-2">Trip Number</th>
              <th class="tw-px-4 tw-py-2">Destination</th>
              <th class="tw-px-4 tw-py-2">Departure Time</th>
              <th class="tw-px-4 tw-py-2">Arrival Time</th>
              <th class="tw-px-4 tw-py-2">Allocated Fuel</th>
              <th class="tw-px-4 tw-py-2">Status</th>
            </tr>
          </thead>
          <tbody id="orderRecords" class="tw-bg-white">
            @foreach($tripTicket as $ticket)
            <tr class="tw-bg-gray-100">
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{$ticket->tripNumber }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{$ticket->destination }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{ \Carbon\Carbon::parse($ticket->departureTime)->format('Y-m-d h:i A') }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{ \Carbon\Carbon::parse($ticket->arrivalTime)->format('Y-m-d h:i A') }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{$ticket->allocatedFuel }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{$ticket->status }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>


    <!-- Vehicle Use Reports -->
    <div class="card-body">
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Vehicle Use Reports</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">Ensure that all vehicle use data are accurate and up-to-date for efficient tracking and reporting.</p>
      </div>
      <div class="tw-px-4">
        <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4">
          <thead class="tw-bg-gray-200 tw-text-gray-700">
            <tr>
              <th class="tw-px-4 tw-py-2">Plate Number</th>
              <th class="tw-px-4 tw-py-2">Type</th>
              <th class="tw-px-4 tw-py-2">Year</th>
              <th class="tw-px-4 tw-py-2">Fuel Type</th>
              <th class="tw-px-4 tw-py-2">Capacity</th>
              <th class="tw-px-4 tw-py-2">Status</th>
            </tr>
          </thead>
          <tbody id="orderRecords" class="tw-bg-white">
            @foreach($tripTicket as $ticket)
            @if ($ticket->vehicle) <!-- Ensure there is an associated vehicle -->
            <tr class="tw-bg-gray-100">
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{ $ticket->vehicle->plateNumber }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">
                {{ $ticket->vehicle->vehicleType === 'light' ? 'Light-Duty Vehicles (e.g., Motorcycle, Van, Small Van)' : ($ticket->vehicle->vehicleType === 'medium' ? 'Medium-Duty Vehicles (e.g., Pickup Trucks, Box Trucks)' : ($ticket->vehicle->vehicleType === 'heavy' ? 'Heavy-Duty Vehicles (e.g., Flatbed Trucks, Mini Trailers)' : 'N/A')) }}
              </td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{ $ticket->vehicle->vehicleYear }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{ ucfirst($ticket->vehicle->vehicleFuelType) }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">{{ $ticket->vehicle->vehicleCapacity }}</td>
              <td class="tw-px-4 tw-py-2 tw-font-bold">
                <span style="color: {{ $ticket->vehicle->vehicleStatus === 'available' ? '#388E3C' : ($ticket->vehicle->vehicleStatus === 'maintenance' ? '#F57C00' : '#E53935') }}">{{ ucfirst($ticket->vehicle->vehicleStatus) }}</span>
              </td>
            </tr>
            @endif
            @endforeach
          </tbody>

        </table>
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