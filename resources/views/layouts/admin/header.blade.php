<header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar avatar-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" /></svg>
                    </span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ Auth::guard('user')->user()->name ?? 'Admin User' }}</div>
                        <div class="mt-1 small text-secondary">Administrator</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    {{-- <a href="#" class="dropdown-item">Profile</a> --}}
                    {{-- <a href="#" class="dropdown-item">Settings</a> --}}
                    {{-- <div class="dropdown-divider"></div> --}}
                    <a href="/proseslogoutadmin" class="dropdown-item">Logout</a>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="navbar-menu">
            <!-- Main navbar menu items can go here if needed, or leave empty if header is just for user actions -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    {{-- <a class="nav-link" href="./">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg>...</svg>
                        </span>
                        <span class="nav-link-title"> Home </span>
                    </a> --}}
                </li>
                 {{-- Remove example dropdown from Tabler --}}
            </ul>
        </div>
    </div>
</header>