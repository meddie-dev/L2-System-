<x-layout.portal.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-md:tw-justify-self-end" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-md:tw-hidden">
      <x-partials.breadcrumb class="tw-bg-white" href="{{route('vendorPortal.dashboard') }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        Card (Delivered)
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
   
    <div class="tw-overflow-x-auto ">
      <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4 | max-sm:tw-text-sm ">

        <thead class="tw-bg-gray-200 tw-text-gray-700 ">
          <tr>
            <th class="tw-px-4 tw-py-2">Delivered </th>
            <th class="tw-px-4 tw-py-2">Status</th>
          </tr>
        </thead>

        <tbody id="reportRecords" class="tw-bg-white">
          @foreach($tripTickets as $tripTicket)
            @if($tripTicket->status === 'delivered')

          <tr class="hover:tw-bg-gray-100">
            <td class="tw-px-4 tw-py-2">
              <a href="{{ route('vendorPortal.card.details', $tripTicket->id ?? '') }}">
                {{ $tripTicket->tripNumber }}
              </a>
            </td>

            <td class="tw-px-4 tw-py-2">
              <span class="tw-text-{{ $tripTicket->status === 'delivered' ? 'green-500' : ($tripTicket->status === 'in_transit' ? 'yellow-500' : ($tripTicket->status === 'delayed' ? 'red-500' : 'blue-500')) }}">
                {{ str_replace('_', ' ', ucfirst($tripTicket->status)) }}
              </span>
            </td>

          </tr>
            @endif
          @endforeach
        </tbody>
      </table>
      <hr>
    </div>
    <div>
      <h3 class="tw-text-md tw-font-semibold tw-text-gray-700 tw-mt-6 tw-mb-2 | max-md:tw-text-sm">Order Table Instructions</h3>
      <div class="tw-text-xs tw-text-gray-600 tw-mb-2 | max-md:tw-text-[11px]">
        <p class="tw-mb-1">In the table above, you can view and manage your order requests created by you by clicking on the "Order No." column of the table. When you click on the "Order No.", you will be redirected to the order details page. </p>
        <p class="tw-mt-2">To add a new order request, click the "Add New" button.</p>
      </div>
    </div>
  </div>

</x-layout.portal.mainTemplate>