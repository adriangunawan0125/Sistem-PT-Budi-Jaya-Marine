<!-- Topbar Full Fixed -->
<nav class="navbar navbar-expand navbar-light bg-white topbar shadow"
     style="position: fixed; top:0; left:224px; right:0; z-index:1030;">

    <!-- Sidebar Toggle (untuk mobile) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Search Form -->
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

        <!-- Notification -->
        <li class="nav-item dropdown no-arrow mx-2">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
               data-toggle="dropdown">
                <i class="fas fa-bell fa-fw"></i>
                @if($adminNotifCount > 0)
                    <span class="badge badge-danger badge-counter">{{ $adminNotifCount }}</span>
                @endif
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="alertsDropdown"
                 style="width:360px; max-height:320px; overflow-y:auto; position:absolute; top:calc(100% + 5px); right:0; z-index:1055;">

                <h6 class="dropdown-header">Notifikasi</h6>

                @forelse($adminNotifs as $notif)
                    <a class="dropdown-item d-flex align-items-start text-truncate notif-item"
                       title="{{ $notif->message }}"
                       data-id="{{ $notif->id }}"
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
                            <div class="font-weight-bold text-truncate">{{ $notif->message }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">
                                {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                @empty
                    <span class="dropdown-item text-center text-muted small">Tidak ada notifikasi</span>
                @endforelse
            </div>
        </li>

        <!-- Divider -->
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- User Info -->
        <li class="nav-item d-none d-lg-block mr-2">
            <span class="nav-link text-gray-600 small">{{ auth()->user()->name }}</span>
        </li>

        <!-- Logout Button -->
        <li class="nav-item">
            <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-1"></i> Logout
            </a>
        </li>

    </ul>
</nav>

<!-- Spacer supaya konten nggak ketutup topbar -->
<div style="height:70px;"></div>

<!-- JS: Update Badge Notifikasi & DB -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Klik bell untuk read all
    const bell = document.getElementById('alertsDropdown');
    if (bell) {
        bell.addEventListener('click', function () {
            const badge = bell.querySelector('.badge-counter');
            if (badge) badge.style.display = 'none';

            fetch("{{ route('admin.notif.readall') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
            })
            .then(res => res.json())
            .then(data => console.log('Semua notifikasi sudah dibaca'))
            .catch(err => console.error(err));
        });
    }

    // Klik notif individual untuk langsung tandai dibaca
    const notifItems = document.querySelectorAll('.notif-item');
    notifItems.forEach(item => {
        item.addEventListener('click', function(e) {
            const id = this.dataset.id;
            fetch("/admin/notifikasi/read/" + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                const badge = document.querySelector('.badge-counter');
                if (badge) {
                    let count = parseInt(badge.innerText);
                    if(count > 1){
                        badge.innerText = count - 1;
                    } else {
                        badge.remove();
                    }
                }
            })
            .catch(err => console.error(err));
        });
    });

});
</script>
