<x-layout.authTemplate>
  <div class="tw-relative tw-flex tw-justify-center tw-mx-auto tw-h-[82vh] tw-bg-blue-500 tw-shadow-lg  tw-overflow-hidden">
    <div class="tw-relative tw-z-10 tw-flex tw-flex-col tw-justify-center tw-items-center ">
      <img
        src="/img/logo.png"
        alt="Logo"
        style="max-width: 250px; max-height: 50px; filter: brightness(0) invert(1);" />
      <h2 class="tw-text-2xl tw-py-3 tw-text-white tw-font-extralight">Fuel Management</h2>
      <div class="tw-mx-auto tw-text-sm tw-mb-6 tw-mt-3">
        <style>
          input {
            width: 40px;
            height: 50px;
            font-size: 20px;
            text-align: center;
            border-radius: 5px;
            margin: 5px;
          }

          @media (max-width: 768px) {
            input {
              width: 28px;
              height: 35px;
              font-size: 15px;
              margin: 1px;
            }
          }
        </style>

        <form action="{{ route('gasStation.verify') }}" method="POST">
          @csrf
          @for ($i = 0; $i < 12; $i++)
            <input type="text" name="cardNumber[]" maxlength="1" class="otp-input" id="input{{$i}}" autocomplete="off" />
          @endfor
        </form>

        @if(session('error'))
        <p class="tw-text-white tw-text-lg tw-mt-3">{{ session('error') }}</p>
        @endif
      </div>

    </div>
    <div class="tw-absolute tw-bottom-[-3rem] tw-left-[-10rem] tw-rotate-[-35deg] tw-opacity-30">
      <img src="/img/logo_white.png" class="tw-w-[500px] tw-object-cover tw-h-[250px] tw-object-top">
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let inputs = document.querySelectorAll(".otp-input");
      let form = document.querySelector("form");

      inputs.forEach((input, index) => {
        input.addEventListener("input", function() {
          if (this.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus(); // Move to the next input
          } else if (this.value.length === 1 && index === inputs.length - 1) {
            form.submit(); // Submit form when last input is filled
          }
        });

        input.addEventListener("keydown", function(event) {
          if (event.key === "Backspace" && this.value.length === 0 && index > 0) {
            inputs[index - 1].focus(); // Move back to previous input
          }
        });
      });
    });
  </script>
</x-layout.authTemplate>
