<!-- Topbar Full Fixed -->
<nav class="navbar navbar-expand navbar-light bg-white topbar shadow"
     style="position: fixed; top:0; left:224px; right:0; z-index:1030;">

    <!-- Sidebar Toggle (mobile) -->
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

    <!-- RIGHT -->
    <ul class="navbar-nav ml-auto align-items-center">

        <!-- 🔔 NOTIF OWNER -->
        <li class="nav-item dropdown no-arrow mx-2">
            <a class="nav-link dropdown-toggle"
               href="#"
               id="ownerAlertsDropdown"
               role="button"
               data-toggle="dropdown">
                <i class="fas fa-bell fa-fw"></i>

                @if($ownerNotifCount > 0)
                    <span class="badge badge-danger badge-counter">
                        {{ $ownerNotifCount }}
                    </span>
                @endif
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="ownerAlertsDropdown"
                 style="width:360px; max-height:320px; overflow-y:auto;">

                <h6 class="dropdown-header">Notifikasi Owner</h6>

                @forelse($ownerNotifs as $notif)
                    <a class="dropdown-item d-flex align-items-start owner-notif-item"
                       href="{{ route('laporan-harian', $notif->data_id) }}"
                       data-id="{{ $notif->id }}">

                        <div class="mr-3">
                            <div class="icon-circle bg-success">
                                <i class="fas fa-wallet text-white"></i>
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

        <!-- Divider -->
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- User -->
        <li class="nav-item d-none d-lg-block mr-2">
            <span class="nav-link text-gray-600 small">
                {{ auth()->user()->name }}
            </span>
        </li>

        <!-- Logout -->
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

<!-- Spacer -->
<div style="height:70px;"></div>

<!-- CSRF -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog"
     aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin keluar dari sistem?
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Batal
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- NOTIF SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // klik satu notifikasi
    document.querySelectorAll('.owner-notif-item').forEach(item => {
        item.addEventListener('click', function () {

            const notifId = this.dataset.id;

            fetch(`/owner/notifikasi/read/${notifId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const badge = document.querySelector('.badge-counter');
            if (badge) badge.remove();
        });
    });

    // klik lonceng → read all
    document.getElementById('ownerAlertsDropdown')?.addEventListener('click', function () {
        fetch(`/owner/notifikasi/read-all`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const badge = document.querySelector('.badge-counter');
        if (badge) badge.remove();
    });

});
</script>
