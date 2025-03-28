<x-layout.dashboardTemplate>
  <div class="container-fluid ">
    <div class=" tw-mx-auto tw-mt-3 tw-mb-6 tw-space-y-6  tw-bg-white tw-rounded-lg tw-shadow-lg tw-p-8" data-aos="fade">
      <div class=" tw-flex tw-flex-col tw-justify-center tw-items-center tw-mt-3 tw-h-full tw-opacity-20 ">
        <img
         class="max-sm:tw-w-[50%]"
          src="/img/logo.png"
          alt="Logo"
          style="max-width: 250px; max-height: 50px; filter: brightness(0.2) grayscale(1) contrast(10);" 
          fetchpriority="high"/>
      </div>
      
      {{ $slot }}
      
    </div>
  </div>

</x-layout.dashboardTemplate>