<x-layout.authTemplate>
    <div style="margin-top: 20vh" class="d-flex h-100 text-center align-items-center justify-content-center">
        <div class="container tw-relative">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="mb-2">
                        <h1 class="display-1">404</h1>
                    </div>
                    <p class="lead tw-mb-4">This requested URL was not found on this server.</p>
                    <a href="
                        @if (Auth::check())
                            @if (Auth::user()->hasRole('Super Admin'))
                                /superadmin/dashboard
                            @elseif (Auth::user()->hasRole('Admin'))
                                /admin/dashboard
                            @elseif (Auth::user()->hasRole('Staff'))
                                /staff/dashboard
                            @elseif (Auth::user()->hasRole('Driver'))
                                /driver/dashboard
                            @elseif (Auth::user()->hasRole('Vendor'))
                                /portal/vendor/dashboard
                            @endif
                        @else
                            /login
                        @endif" class="btn btn-primary">
                        @if (Auth::check())
                        Return to Dashboard
                        @else
                        Return to login
                        @endif
                    </a>
                </div>
            </div>
            <div class="tw-absolute tw-bottom-[-13rem] tw-left-[-24rem] tw-opacity-30 | max-md:tw-bottom-[-7rem] max-md:tw-left-[-12rem] ">
                <img src="/img/logo_white.png" class="tw-w-[900px] tw-object-cover tw-h-[470px] tw-object-top tw-contrast-50 | max-md:tw-w-[00px] max-md:tw-h-[250px]">
            </div>
        </div>
    </div>
</x-layout.authTemplate>