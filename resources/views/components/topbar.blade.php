<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
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

    <!-- Right Navbar -->
    <ul class="navbar-nav ml-auto align-items-center">

        <!-- NOTIFIKASI -->
        <li class="nav-item dropdown no-arrow mx-2">
            <a class="nav-link dropdown-toggle d-flex align-items-center"
               href="#"
               id="alertsDropdown"
               role="button"
               data-toggle="dropdown"
               aria-haspopup="true"
               aria-expanded="false">

                <i class="fas fa-bell fa-fw"></i>

                @if($adminNotifCount > 0)
                    <span class="badge badge-danger badge-counter ml-1">
                        {{ $adminNotifCount }}
                    </span>
                @endif
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="alertsDropdown">

                <h6 class="dropdown-header">Notifikasi</h6>

                @forelse($adminNotifs as $notif)
                    <a class="dropdown-item d-flex align-items-center"
                       href="{{ $notif->type === 'contact'
                            ? route('contact.show', $notif->data_id)
                            : route('admin.calonmitra') }}">
                        <div class="mr-3">
                            <div class="icon-circle bg-warning">
                                <i class="fas fa-envelope text-white"></i>
                            </div>
                        </div>
                        <div class="small font-weight-bold">
                            {{ $notif->message }}
                        </div>
                    </a>
                @empty
                    <span class="dropdown-item text-center text-muted small">
                        Tidak ada notifikasi
                    </span>
                @endforelse
            </div>
        </li>

        <!-- DIVIDER -->
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- LOGOUT -->
        <li class="nav-item ml-2">
            <a href="#"
               class="btn btn-danger btn-sm d-flex align-items-center"
               data-toggle="modal"
               data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
                Logout
            </a>
        </li>

    </ul>
</nav>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Logout</h5>
                <button class="close" type="button" data-dismiss="modal">
                    <span>×</span>
                </button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin logout?
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">
                    Batal
                </button>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
