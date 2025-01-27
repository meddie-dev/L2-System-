<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark" :notifications="$notifications">
  <!-- Navbar Brand-->
  <a href="/" class="navbar-brand ps-3">
    <img
      src="/img/logo.png"
      alt="Logo"
      style="max-width: 150px; max-height: 50px; filter: brightness(0) invert(1);" />
  </a>

  <!-- Sidebar Toggle-->
  <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

  <!-- Navbar Search-->
  <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
    <!-- <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
          </div> -->
  </form>


  <!-- Navbar Notifications -->
  <div class="collapse navbar-collapse px-2 ">
    <ul class="navbar-nav ms-auto">
      <!-- Notification Icon -->
      <li class="nav-item dropdown">
        <a class="nav-link position-relative" href="#" id="notificationIcon" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-bell"></i> <!-- Bell icon -->
          @php
          $notifications = auth()->user()->unreadNotifications;
          @endphp
          @if($notifications->count() > 0)
          <!-- Red circle badge -->
          <span
            class="position-absolute top-10 start-70 translate-middle badge rounded-pill bg-danger"
            id="notificationDot"
            style="width: 10px; height: 10px; z-index: 1; padding: 0;">
          </span>
          @endif
        </a>

        <div class="dropdown-menu dropdown-menu-end p-0 border-0 shadow | max-sm:tw-translate-x-[15%]" aria-labelledby="notificationIcon" id="notificationList" style="width: 350px;">
          <div class=" p-3 text-white | tw-bg-[#232529d3]">
            <h5 class="mb-0">Notifications</h5>
          </div>
          <div class="list-group list-group-flush" style="max-height: 200px; overflow-y: auto;">
            @if($notifications->isEmpty())
            <span class="list-group-item list-group-item-action">No notifications</span>
            @else
            @foreach($notifications as $notification)
            <a class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'bg-light' }}" href="{{ route('notifications.show', $notification->id) }} ">
              <div class="d-flex w-100 justify-content-center align-items-center ">
                <div class="flex-grow-1">
                  <p class="mb-2 | max-sm:tw-text-sm">{{ $notification->data['message'] }}</p>
                  <div class="d-flex justify-content-between">
                    <div class="text-muted" style="font-size: 0.8em;">{{ $notification->created_at->timezone('Asia/Manila')->format('M d, Y') }}</div>
                    <div class="text-muted" style="font-size: 0.8em;">{{ $notification->created_at->timezone('Asia/Manila')->format('h:i A') }}</div>
                  </div>
                </div>
              </div>
            </a>
            @endforeach
            @endif
          </div>
        </div>
        <hr class="dropdown-divider" />
      </li>
    </ul>
  </div>

  <!-- Navbar Settings -->
  <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4 max-sm:tw-hidden">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <li>
          <a class="dropdown-item" href="{{route('settings.index')}}">Settings</a>
        </li>
        <li>
          <hr class="dropdown-divider" />
        </li>
        <li>
          <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="dropdown-item">Logout</button>
          </form>
        </li>
      </ul>
    </li>
  </ul>
</nav>