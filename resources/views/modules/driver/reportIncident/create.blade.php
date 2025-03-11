<x-layout.portal.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-tw-hidden | max-md:" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route('vendorPortal.dashboard') }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="false">
        <div class="tw-flex tw-items-center tw-justify-center">
          Make Report
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
    <div class="tw-overflow-x-auto tw-mb-6">
      <!-- Report Information -->
      <p class="tw-text-sm tw-text-gray-500 tw-mb-4 | max-md:tw-text-xs"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>

      <form action="{{ route('driver.trip.report.store', $tripTicket->id) }}" enctype="multipart/form-data" method="POST" class="tw-px-4">
        @csrf
        <!-- Report Number -->
        <div class="tw-mb-3">
          <label for="reportNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Report Number<span class="tw-text-red-500">*</span></label>
          <input type="text" id="reportNumber" name="reportNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('reportNumber') is-invalid @enderror" placeholder="ENTER ORDER NUMBER" value="{{ strtoupper(Str::random(20)) }}" readonly>
          @error('reportNumber')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Incident Type -->
        <div class="tw-mb-3">
          <label for="incident_type" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Incident Type<span class="tw-text-red-500">*</span></label>
          <select name="incident_type" id="incident_type" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 | max-md:tw-text-xs @error('incident_type') is-invalid @enderror" required>
            <option value="">Select an Incident Type</option>
            <option value="traffic">Traffic</option>
            <option value="accident">Accident</option>
            <option value="theft">Theft</option>
            <option value="damage">Damage</option>
            <option value="mechanical_issue">Mechanical Issue</option>
          </select>
          @error('incident_type')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Proof -->
        <div class="tw-mb-3">
          <label for="proof" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Proof<span class="tw-text-red-500">*</span></label>
          <input type="file" name="proof" id="proof" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 | max-md:tw-text-xs @error('proof') is-invalid @enderror" required>
          @error('proof')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Description -->
        <div class="tw-mb-3">
          <label for="description" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Description<span class="tw-text-red-500">*</span></label>
          <textarea name="description" id="description" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 | max-md:tw-text-xs @error('description') is-invalid @enderror" rows="4" placeholder="Write your description here..."></textarea>
          @error('description')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <button type="submit" class=" tw-text-sm tw-font-medium tw-text-gray-200  tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white  hover:tw-text-gray-600 tw-float-right">Submit Report</button>
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