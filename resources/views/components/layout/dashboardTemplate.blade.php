<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Logistics II</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Favicon -->
  <link href="/img/favicon.ico" rel="icon">

  <!-- Template Library -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.min.css" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>


  @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="tw-bg-gray-100 tw-p-8 | max-sm:tw-p-4">

  <div class="sb-nav-fixed">
    @include('components.partials.navbar')

    <div id="layoutSidenav">
      <div id="layoutSidenav_nav">
        @include('components.partials.sidebar')
      </div>

      <div id="layoutSidenav_content">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-custom-position" role="alert" id="autoHideAlert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show alert-custom-position" role="alert" id="autoHideAlert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <main class="container-fluid tw-px-4 max-sm:tw-px-0">
          <!-- Page Content goes here -->
          {{ $slot }}
        </main>

        @include('components.partials.footer')
      </div>

      <!-- Scroll Top -->
      <a href="#" id="scroll-top" class="scroll-top tw-flex tw-items-center tw-justify-center"><i class="fas fa-arrow-up  tw-text-gray-300"></i></a>

      <!-- Preloader -->
      <div id="preloader"></div>

    </div>
  </div>

  <!-- Template Library -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
</body>

</html>