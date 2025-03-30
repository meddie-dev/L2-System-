<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="false">
        Vendor Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        Vendor Profiles<span class="tw-font-semibold tw-text-sm tw-opacity-20 tw-mt-1 ">{{ $user->firstName }} {{ $user->lastName }}</span>
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-overflow-x-auto ">
      <!-- Order Table -->
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Order Information</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">View and track the details of this order.</p>
      </div>
      <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4 | max-sm:tw-text-sm ">

        <thead class="tw-bg-gray-200 tw-text-gray-700 ">
          <tr>
            <th class="tw-px-4 tw-py-2">Order No.</th>
            <th class="tw-px-4 tw-py-2">Weight (kg)</th>
            <th class="tw-px-4 tw-py-2">Amount</th>
            <th class="tw-px-4 tw-py-2">Delivery Address</th>
            <th class="tw-px-4 tw-py-2">Delivery Request Date</th>
            <th class="tw-px-4 tw-py-2">Status</th>
          </tr>
        </thead>

        <tbody id="reportRecords" class="tw-bg-white">
          @foreach($orders as $order)
          <tr class="hover:tw-bg-gray-100">
            <td class="tw-px-4 tw-py-2">{{ $order->orderNumber }}</td>
            <td class="tw-px-4 tw-py-2">{{ $order->weight }}</td>
            <td class="tw-px-4 tw-py-2">&#x20B1;{{ number_format($order->amount, 2) }}</td>
            <td class="tw-px-4 tw-py-2">{{ $order->deliveryAddress }}</td>
            <td class="tw-px-4 tw-py-2">{{ $order->deliveryRequestDate }}</td>
            <td class="tw-px-4 tw-py-2">
              <span class="tw-text-{{ $order->approval_status === 'approved' ? 'green-500' : ($order->approval_status === 'pending' ? 'yellow-500' : ($order->approval_status === 'reviewed' ? 'blue-500' : 'red-500')) }}">
                {{ ucfirst($order->approval_status) }}
              </span>
            </td>

          </tr>
          @endforeach
        </tbody>
      </table>

      <!-- Document Table -->
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Document Information</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">View and track the details of this document.</p>
      </div>
      <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4 | max-sm:tw-text-sm ">

        <thead class="tw-bg-gray-200 tw-text-gray-700 ">
          <tr>
            <th class="tw-px-4 tw-py-2">Document No.</th>
            <th class="tw-px-4 tw-py-2">Document Name</th>
            <th class="tw-px-4 tw-py-2">Created Date</th>
            <th class="tw-px-4 tw-py-2">Status</th>
          </tr>
        </thead>

        <tbody id="reportRecords" class="tw-bg-white">
          @foreach($documents as $document)
          <tr class="hover:tw-bg-gray-100">
            <td class="tw-px-4 tw-py-2">{{ $document->documentNumber }}</td>
            <td class="tw-px-4 tw-py-2">{{ $document->documentName }}</td>
            <td class="tw-px-4 tw-py-2">{{ $document->created_at->format('Y-m-d') }}</td>
            <td class="tw-px-4 tw-py-2">
              <span class="tw-text-{{ $document->approval_status === 'approved' ? 'green-500' : ($document->approval_status === 'pending' ? 'yellow-500' : ($document->approval_status === 'reviewed' ? 'blue-500' : 'red-500')) }}">
                {{ ucfirst($document->approval_status) }}
              </span>
            </td>

          </tr>
          @endforeach
        </tbody>
      </table>

      <!-- Payment Table -->
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Payment Information</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">View and track the details of these payments.</p>
      </div>
      <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4 | max-sm:tw-text-sm">

        <thead class="tw-bg-gray-200 tw-text-gray-700 ">
          <tr>
            <th class="tw-px-4 tw-py-2">Payment No.</th>
            <th class="tw-px-4 tw-py-2">Created Date</th>
            <th class="tw-px-4 tw-py-2">Status</th>
          </tr>
        </thead>

        <tbody id="reportRecords" class="tw-bg-white">
          @foreach($payments as $payment)
          <tr class="hover:tw-bg-gray-100">
            <td class="tw-px-4 tw-py-2">
                {{ $payment->paymentNumber }}
            </td>
            <td class="tw-px-4 tw-py-2">{{ $payment->created_at->format('Y-m-d') }}</td>
            <td class="tw-px-4 tw-py-2">
              <span class="tw-text-{{ $payment->approval_status === 'approved' ? 'green-500' : ($payment->approval_status === 'pending' ? 'yellow-500' : ($payment->approval_status === 'reviewed' ? 'blue-500' : 'red-500')) }}">
                {{ ucfirst($payment->approval_status) }}
              </span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <!-- Vehicle Reservation Table -->
      <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <h2 class="tw-text-md tw-font-semibold tw-mb-1 | max-md:tw-text-sm">Vehicle Reservation Information</h2>
        <p class="tw-text-xs | max-md:tw-text-xs">View and track the details of these vehicle reservations.</p>
      </div>
      <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4 | max-sm:tw-text-sm">

        <thead class="tw-bg-gray-200 tw-text-gray-700 ">
          <tr>
            <th class="tw-px-4 tw-py-2">Reservation No.</th>
            <th class="tw-px-4 tw-py-2">Date</th>
            <th class="tw-px-4 tw-py-2">Status</th>
          </tr>
        </thead>

        <tbody id="reportRecords" class="tw-bg-white">
          @foreach($vehicleReservations as $vehicleReservation)
          <tr class="hover:tw-bg-gray-100">
            <td class="tw-px-4 tw-py-2">
                {{ $vehicleReservation->reservationNumber }}
            </td>
            <td class="tw-px-4 tw-py-2">{{ $vehicleReservation->created_at->format('Y-m-d') }}</td>
            <td class="tw-px-4 tw-py-2">
              <span class="tw-text-{{ $vehicleReservation->approval_status === 'approved' ? 'green-500' : ($vehicleReservation->approval_status === 'pending' ? 'yellow-500' : 'red-500') }}">
                {{ ucfirst($vehicleReservation->approval_status) }}
              </span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div>
      <div class="tw-flex tw-items-center tw-justify-start tw-my-6">
        <a href="{{ route('superadmin.vendors.index') }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 | max-md:tw-p-3 ">
          <i class="fa-solid fa-arrow-left tw-mr-2 | max-md:tw-text-xs"></i>
          Back
        </a>
      </div>
    </div>
      <hr>
    </div>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2 | max-md:tw-text-sm">Order Table Instructions</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2 | max-md:tw-text-[12px]">
        <p class="tw-mb-1">In the table above, you can view and manage your order requests created by you by clicking on the "Order No." column of the table. When you click on the "Order No.", you will be redirected to the order details page. </p>
        <p class="tw-mt-2">To add a new order request, click the "Add New" button.</p>
      </div>
    </div>
  </div>

</x-layout.mainTemplate>