<x-layout.portal.portalDashboardTemplate >
  <div class="container-fluid" data-aos="fade">
    <div class=" tw-mx-auto tw-mt-3 tw-mb-6 tw-space-y-6  tw-bg-white tw-rounded-lg tw-shadow-lg tw-p-8" data-aos="fade">
      <div class=" tw-flex tw-flex-col tw-justify-center tw-items-center tw-mt-3 tw-h-full tw-opacity-20">
        <img
          class="max-md:tw-w-[50%]"
          src="/img/logo.png"
          alt="Logo"
          style="max-width: 230px; max-height: 50px; filter: brightness(0.2) grayscale(1) contrast(10);" />
        <h2 class="tw-text-2xl tw-py-3 tw-text-gray-700 tw-font-extralight | max-sm:tw-text-[16px] max-sm:tw-py-1">Vendor Portal</h2>
      </div>
      
      {{ $slot }}
      
      
    </div>
  </div>

</x-layout.portal.portalDashboardTemplate>