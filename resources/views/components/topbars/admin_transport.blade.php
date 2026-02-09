<!-- Topbar Full Fixed -->
<nav class="navbar navbar-expand navbar-light bg-white topbar shadow"
     style="position: fixed; top:0; left:224px; right:0; z-index:1030;">

    <!-- Sidebar Toggle (untuk mobile) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Search Form (Quick Menu Live Search) -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" id="topbarQuickMenuForm">
        <div class="input-group position-relative">
            <input type="text"
                   class="form-control bg-light border-0 small"
                   placeholder="Search menu..."
                   aria-label="Search"
                   id="topbarQuickMenuInput"
                   autocomplete="off">
            <div class="input-group-append">
                <button class="btn btn-primary" style="margin-left:5px" type="button" id="topbarQuickMenuBtn">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
            <!-- Dropdown hasil search -->
            <ul id="topbarQuickMenuDropdown" class="list-group position-absolute bg-white shadow"
                style="top:100%; left:0; right:0; z-index:1050; display:none; max-height:250px; overflow-y:auto;"></ul>
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
                Apakah kamu yakin ingin keluar dari Sistem?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
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


<!-- Spacer supaya konten nggak ketutup topbar -->
<div style="height:70px;"></div>

<!-- JS -->
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

    // ================== Topbar Quick Menu Live Search ==================
    const menuMap = {
        'Dashboard': '/admin-transport',
        'Invoice': '/invoice',
        'Mitra': '/admin-transport/mitra',
        'Ex Mitra': '/admin-transport/mitra/berakhir',
        'Pengeluaran Internal': '/pengeluaran_internal',
        'Pengeluaran Transport': '/pengeluaran_transport',
        'Pengeluaran Pajak': '/pengeluaran_pajak',
        'Daftar Unit': '/admin-transport/unit',
        'Calon Mitra': '/calon-mitra',
        'Jaminan Mitra': '/jaminan-mitra',
        'Pesan': '/contact'
    };

    const searchInput = document.getElementById('topbarQuickMenuInput');
    const searchDropdown = document.getElementById('topbarQuickMenuDropdown');
    const searchBtn = document.getElementById('topbarQuickMenuBtn');

    function filterMenu() {
        const query = searchInput.value.toLowerCase();
        searchDropdown.innerHTML = '';
        let hasResult = false;
        for(const name in menuMap){
            if(name.toLowerCase().includes(query)){
                hasResult = true;
                const li = document.createElement('li');
                li.textContent = name;
                li.className = 'list-group-item list-group-item-action';
                li.style.cursor = 'pointer';
                li.addEventListener('click', function(){
                    window.location.href = menuMap[name];
                });
                searchDropdown.appendChild(li);
            }
        }
        searchDropdown.style.display = hasResult ? 'block' : 'none';
    }

    searchInput.addEventListener('input', filterMenu);

    // Klik tombol search → pilih first result
    searchBtn.addEventListener('click', function(){
        const firstItem = searchDropdown.querySelector('li');
        if(firstItem) firstItem.click();
    });

    // Enter di input → pilih first result
    searchInput.addEventListener('keydown', function(e){
        if(e.key === 'Enter'){
            e.preventDefault();
            const firstItem = searchDropdown.querySelector('li');
            if(firstItem) firstItem.click();
        }
    });

    // Klik luar → hide dropdown
    document.addEventListener('click', function(e){
        if(!searchInput.contains(e.target) && !searchDropdown.contains(e.target)){
            searchDropdown.style.display = 'none';
        }
    });

});
</script>
