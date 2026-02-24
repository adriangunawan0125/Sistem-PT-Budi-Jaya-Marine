<style>
.sidebar-brand {
    min-height: 90px;
}

.sidebar-brand-text {
    text-align: center;
}

.sidebar-brand-text .brand-title {
    font-size: 1.2rem;
    font-weight: 800;
    letter-spacing: 1px;
    line-height: 1.2;
}

.nav-item.active > .nav-link {
    font-weight: 700;
}

.nav-link i {
    font-size: 1.05rem;
}

#accordionSidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    overflow-y: auto;
    width: 224px;
    z-index: 1030;
}

#accordionSidebar::-webkit-scrollbar {
    width: 6px;
}
#accordionSidebar::-webkit-scrollbar-thumb {
    background-color: rgba(255,255,255,.25);
    border-radius: 10px;
}

#content-wrapper,
#content {
    margin-left: 110px;
}

@media (max-width: 768px) {
    #accordionSidebar {
        position: relative;
        width: 100%;
        height: auto;
    }

    #content-wrapper,
    #content {
        margin-left: 0;
    }
}
</style>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- BRAND -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-4"
       href="{{ route('owner.dashboard') }}">
        <div class="sidebar-brand-text">
            <div class="brand-title">
                OWNER<br>TRANSPORT
            </div>
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- DASHBOARD -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('owner.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Kelola</div>

    <!-- LAPORAN PEMASUKAN -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse"
           data-target="#collapsePemasukan">
            <i class="fas fa-fw fa-coins"></i>
            <span>Laporan Pemasukan</span>
        </a>
        <div id="collapsePemasukan" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('laporan-harian') }}">
                    <i class="fas fa-calendar-day mr-2"></i> Hari Ini
                </a>
                <a class="collapse-item" href="{{ route('laporan-bulanan') }}">
                    <i class="fas fa-calendar-alt mr-2"></i> Bulanan
                </a>
            </div>
        </div>
    </li>

    <!-- LAPORAN PENGELUARAN -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse"
           data-target="#collapsePengeluaran">
            <i class="fas fa-fw fa-money-bill-wave"></i>
            <span>Laporan Pengeluaran</span>
        </a>
        <div id="collapsePengeluaran" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('owner_transport.laporan_pengeluaran') }}">
                    <i class="fas fa-wallet mr-2"></i> Internal
                </a>
                <a class="collapse-item" href="{{ route('pengeluaran_transport.rekap') }}">
                    <i class="fas fa-truck mr-2"></i> Transport
                </a>
                <a class="collapse-item" href="{{ route('pengeluaran_pajak.rekap') }}">
                    <i class="fas fa-car mr-2"></i> Pajak Mobil
                </a>
            </div>
        </div>
    </li>

    
    <!-- INVOICE -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse"
           data-target="#collapseInvoice">
            <i class="fas fa-fw fa-file-invoice"></i>
            <span>Laporan Invoice</span>
        </a>
        <div id="collapseInvoice" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('invoice.rekap') }}">
                    <i class="fas fa-file-invoice-dollar mr-2"></i> Invoice Mitra
                </a>
            </div>
        </div>
    </li>

    <!-- MITRA -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse"
           data-target="#collapseMitra">
            <i class="fas fa-fw fa-users"></i>
            <span>Mitra & Ex-Mitra</span>
        </a>
        <div id="collapseMitra" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('mitra.aktif') }}">
                    <i class="fas fa-user-check mr-2"></i> Mitra Aktif
                </a>
                <a class="collapse-item" href="{{ route('mitra.ex') }}">
                    <i class="fas fa-user-slash mr-2"></i> Ex-Mitra
                </a>
            </div>
        </div>
    </li>

    <!-- KELOLA AKUN -->
    <li class="nav-item {{ request()->routeIs('akun.*') ? 'active' : '' }}">
        <a class="nav-link"
           href="{{ route('akun.index') }}">
            <i class="fas fa-fw fa-user-cog"></i>
            <span>Kelola Akun</span>
        </a>
    </li>
    <hr class="sidebar-divider d-none d-md-block">

</ul>
