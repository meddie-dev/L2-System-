<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Logistics II</title>

  <!-- Favicon -->
  <link href="/img/favicon.ico" rel="icon">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">


  <!-- Template Library -->
  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="tw-bg-primary">
  <div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
      <main>
        <div class="container-fluid">
          <div class="tw-flex tw-justify-between tw-mx-auto tw-mt-3 tw-mb-6 tw-space-y-6  tw-bg-white tw-p-8">
            <div style="text-align: center;" class=" tw-flex tw-flex-col tw-justify-center tw-items-centertw-h-full ">
              <img
                src="{{ public_path('img/logo.png') }}"
                alt="Logo"
                style="max-width: 200px; max-height: 50px; " />
            </div>

            <div>
              <div style="text-align: right; margin: 24px 0;">
                <p style="font-size: 14px; color: #718096">Date Generated: {{ date('F j, Y') }}</p>
                <p style="font-size: 14px; color: #718096;">1234 Business Rd, Suite 100</p>
                <p style="font-size: 14px; color: #718096;">Phone: (123) 456-7890</p>
                <p style="font-size: 14px; color: #718096;">Email: info@dsglobalholding.com</p>
              </div>
            </div>
          </div>

          <div>{{ $slot }}</div>
        </div>
      </main>
    </div>
  </div>
  <!-- Template Library -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
</body>

</html>