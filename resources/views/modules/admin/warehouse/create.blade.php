<x-layout.portal.mainTemplate>
  <nav class="tw-flex | max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : (Auth::user()->hasRole('Vendor') ? 'vendorPortal.dashboard' : 'staff.dashboard'))) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="false">
        Inventory
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        Product: {{ $product->name }}
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="tw-px-4">
    <p class="tw-text-sm tw-text-gray-500 | max-md:tw-text-xs"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>

    <form action="{{ route('admin.warehouse.update', $product->id) }}" method="POST" class="tw-mt-6">
      @csrf
      @method('PATCH')

      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-text-sm | max-md:tw-grid-cols-1 max-md:tw-gap-2 ">
        <!-- Name -->
        <div class="tw-mb-4">
          <label for="name" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Name<span class="tw-text-red-500">*</span></label>
          <input type="text" id="name" name="name" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('name') is-invalid @enderror" placeholder="Enter product name" value="{{ $product->name }}" readonly>
          @error('name')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Price -->
        <div class="tw-mb-4">
          <label for="price" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Price<span class="tw-text-red-500">*</span></label>
          <input type="number" id="price" name="price" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('price') is-invalid @enderror" placeholder="Enter price" value="{{ $product->price }}" readonly step="0.01">
          @error('price')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Weight -->
        <div class="tw-mb-4">
          <label for="weight" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Weight (Kg)<span class="tw-text-red-500">*</span></label>
          <input type="number" id="weight" name="weight" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('weight') is-invalid @enderror" placeholder="Enter weight (kg)" value="{{ $product->weight }}" readonly step="0.01">
          @error('weight')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Stock -->
        <div class="tw-mb-4">
          <label for="stock" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Stock<span class="tw-text-red-500">*</span></label>
          <input type="number" id="stock" name="stock" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm| max-md:tw-text-xs @error('stock') is-invalid @enderror" placeholder="Enter stock quantity" value="{{ $product->stock }}" required min="0">
          @error('stock')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>
      </div>

      <!-- Description -->
      <div class="tw-mb-4">
        <label for="description" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Description<span class="tw-text-red-500">*</span></label>
        <textarea id="description" name="description" class="tw-block tw-w-full tw-h-20 tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('description') is-invalid @enderror" placeholder="Enter product description" readonly>{{ $product->description }}</textarea>
        @error('description')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
      </div>

      <div class="tw-my-6 tw-flex tw-justify-end">
        <button type="submit" id="submitBtn" class="tw-bg-indigo-600 tw-text-white tw-px-6 tw-py-2 tw-rounded-md tw-shadow-md hover:tw-bg-indigo-700">Proceed</button>
      </div>
    </form>
    
  </div>
</x-layout.portal.mainTemplate>