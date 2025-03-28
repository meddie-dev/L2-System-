<x-layout.mainTemplate>
  <nav class="tw-flex tw-justify-between | max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="false">
        Document Tracking
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        Manage Documents
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="card-body tw-px-4">
    <div class="tw-overflow-x-auto ">
      <!-- Breadcrumbs Navigation -->
      <div class="tw-flex tw-flex-wrap tw-items-center tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white | max-md:tw-p-4">
        <a href="{{ route('admin.document.manage') }}" class="hover:tw-text-white hover:tw-underline tw-font-semibold">
          Documents
        </a>

        @foreach($breadcrumbs as $index => $breadcrumb)
        <span class="tw-mx-1">/</span>
        @if($index !== count($breadcrumbs) - 1)
        <a href="{{ route('admin.document.manage', ['folder_id' => $breadcrumb['id']]) }}" class="hover:tw-text-white hover:tw-underline tw-font-semibold">
          {{ $breadcrumb['name'] }}
        </a>
        @else
        <span class="tw-text-white">{{ $breadcrumb['name'] }}</span>
        @endif
        @endforeach
      </div>

      <!-- File Manager Grid Layout -->
      <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-4 lg:tw-grid-cols-6 tw-gap-4 tw-bg-white tw-p-4">
        @foreach($files as $file)
        <div class="tw-flex tw-flex-col tw-items-center tw-p-2 tw-bg-gray-100 tw-rounded-lg hover:tw-shadow-md">
          @if($file['mimeType'] === 'application/vnd.google-apps.folder')
          <!-- Folder Item -->
          <a href="{{ route('admin.document.manage', ['folder_id' => $file['id']]) }}" class="tw-text-blue-600 tw-text-center">
            <i class="fa-solid fa-folder tw-text-6xl tw-text-yellow-500" loading="lazy"></i>
            <p class="tw-mt-2 tw-text-sm">{{ ucfirst(str_replace('_', ' ', $file['name'])) }}</p>
          </a>
          @else
          <!-- File Item -->
          <a href="{{ $file['webViewLink'] }}" target="_blank" class="tw-text-center">
            <i class="fa-solid fa-file tw-text-6xl tw-text-gray-500" loading="lazy"></i>
            <p class="tw-mt-2 tw-text-sm tw-text-gray-800">{{ $file['name'] }}</p>
          </a>
          @endif
        </div>
        @endforeach
      </div>

      @if($parentFolderId)
      <!-- Back Button -->
      <div class="tw-flex tw-items-center tw-justify-between tw-my-6">
        <a href="{{ route('admin.document.manage', ['folder_id' => $parentFolderId]) }}" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200 tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white hover:tw-text-gray-600">
          <i class="fa-solid fa-arrow-left"></i>
          <span>Back</span>
        </a>
        <a href="https://drive.google.com/drive/folders/17mp2A1McodVzGqwPI6E_UtuPNsq2sAT8" target="_blank" class="tw-flex tw-items-center tw-space-x-1 tw-text-sm tw-font-medium tw-text-gray-200 tw-bg-gray-600 tw-rounded-md tw-px-4 tw-py-2 hover:tw-border hover:tw-border-gray-600 hover:tw-bg-white hover:tw-text-gray-600">
          <i class="fa-solid fa-external-link-alt"></i>
          <span>Open in GDrive</span>
        </a>
      </div>
      @endif


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