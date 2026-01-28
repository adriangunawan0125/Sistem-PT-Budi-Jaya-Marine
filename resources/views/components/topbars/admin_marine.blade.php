<nav class="navbar navbar-expand navbar-light bg-white topbar shadow"
     style="position: fixed; top:0; left:224px; right:0; z-index:1030;">

    <!-- Sidebar Toggle -->
    <button id="sidebarToggleTop"
            class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text"
                   class="form-control bg-light border-0 small"
                   placeholder="Search..."
                   aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- RIGHT -->
    <ul class="navbar-nav ml-auto align-items-center">

        <!-- NOTIF -->
        <li class="nav-item dropdown no-arrow mx-2">
            <a class="nav-link dropdown-toggle"
               href="#"
               id="alertsDropdown"
               role="button"
               data-toggle="dropdown">
                <i class="fas fa-bell fa-fw"></i>

                @if($adminNotifCount > 0)
                    <span class="badge badge-danger badge-counter">
                        {{ $adminNotifCount }}
                    </span>
                @endif
            </a>

            <!-- DROPDOWN FIXED -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="alertsDropdown"
                 style="width:360px; max-height:320px; overflow-y:auto; position:absolute; top:calc(100% + 5px); right:0; z-index:1055;">

                <h6 class="dropdown-header">Notifikasi</h6>

                @forelse($adminNotifs as $notif)
                    <a class="dropdown-item d-flex align-items-start text-truncate"
                       title="{{ $notif->message }}"
                       href="{{ $notif->type === 'unit'
                            ? url('/admin-transport/unit/edit/'.$notif->data_id)
                            : ($notif->type === 'contact'
                                ? route('contact.show', $notif->data_id)
                                : route('calonmitra.show', $notif->data_id)) }}">

                        <div class="mr-3">
                            <div class="icon-circle {{ $notif->type === 'unit' ? 'bg-danger' : 'bg-warning' }}">
                                <i class="{{ $notif->type === 'unit' ? 'fas fa-exclamation-triangle' : 'fas fa-envelope' }} text-white"></i>
                            </div>
                        </div>

                        <div class="small text-truncate" style="max-width:250px;">
                            <div class="font-weight-bold text-truncate">
                                {{ $notif->message }}
                            </div>
                            <div class="text-muted" style="font-size:0.75rem;">
                                {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                @empty
                    <span class="dropdown-item text-center text-muted small">
                        Tidak ada notifikasi
                    </span>
                @endforelse
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- USER -->
        <li class="nav-item d-none d-lg-block mr-2">
            <span class="nav-link text-gray-600 small">
                {{ auth()->user()->name }}
            </span>
        </li>

        <!-- LOGOUT -->
        <li class="nav-item">
            <a href="#"
               class="btn btn-danger btn-sm"
               data-toggle="modal"
               data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-1"></i>
                Logout
            </a>
        </li>
    </ul>
</nav>

<!-- Biar konten nggak ketutup topbar -->
<div style="height:70px;"></div>
