<style>
/* BRAND SIDEBAR */
.sidebar-brand {
    min-height: 90px;
}

.sidebar-brand-icon img {
    width: 48px;
    height: auto;
}

.sidebar-brand-text {
    text-align: center;
}

.sidebar-brand-text .brand-title {
    font-size: 1.3rem;
    font-weight: 800;
    letter-spacing: 1px;
    line-height: 1.2;
}

/* MENU AKTIF */
.nav-item.active > .nav-link {
    font-weight: 700;
}

/* ICON LEBIH PROPORSIONAL */
.nav-link i {
    font-size: 1.05rem;
}

/* KHUSUS PEMASUKAN BIAR TEGAS */
.nav-link.pemasukan span {
    font-weight: 700;
}

#accordionSidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    overflow-y: auto;
    width: 224px; /* default SB Admin */
    z-index: 1030;
}

/* SCROLLBAR HALUS */
#accordionSidebar::-webkit-scrollbar {
    width: 6px;
}
#accordionSidebar::-webkit-scrollbar-thumb {
    background-color: rgba(255,255,255,.25);
    border-radius: 10px;
}

/* KONTEN UTAMA GESER KE KANAN */
#content-wrapper,
#content {
    margin-left: 110px;
}

/* MOBILE MODE */
@media (max-width: 768px) {
    #accordionSidebar {
        position: relative;
        height: auto;
        width: 100%;
    }

    #content-wrapper,
    #content {
        margin-left: 0;
    }
}
</style>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-4" href="/admin-transport">
        <div class="sidebar-brand-text">
            <div class="brand-title">
                ADMIN<br>TRANSPORT
            </div>
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="/admin-transport">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Kelola</div>

    <!-- Kelola Unit -->
    <li class="nav-item {{ request()->is('admin-transport/unit*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUnit">
            <i class="fas fa-fw fa-car"></i>
            <span>Kelola Unit</span>
        </a>
        <div id="collapseUnit"
             class="collapse {{ request()->is('admin-transport/unit*') ? 'show' : '' }}"
             data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/admin-transport/unit">
                    <i class="fas fa-list me-2"></i> Daftar Unit
                </a>
                <a class="collapse-item" href="/admin-transport/unit/create">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Unit
                </a>
            </div>
        </div>
    </li>

    <!-- Kelola Mitra -->
    <li class="nav-item {{ request()->is('admin-transport/mitra*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMitra">
            <i class="fas fa-fw fa-users"></i>
            <span>Kelola Mitra</span>
        </a>
        <div id="collapseMitra"
             class="collapse {{ request()->is('admin-transport/mitra*') ? 'show' : '' }}"
             data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/admin-transport/mitra">
                    <i class="fas fa-user-check me-2"></i> Mitra Kontrak
                </a>
                <a class="collapse-item" href="/admin-transport/mitra/berakhir">
                    <i class="fas fa-user-times me-2"></i> Ex-Mitra
                </a>
                <a class="collapse-item" href="/jaminan_mitra">
                    <i class="fas fa-shield-alt me-2"></i> Data Jaminan
                </a>
               <!--<a class="collapse-item" href="/admin-transport/laporan/mitra">
                    <i class="fas fa-file-alt me-2"></i> Laporan Mitra
                </a>-->
            </div>
        </div>
    </li>

    <!-- Invoice -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInvoice">
            <i class="fas fa-fw fa-file-invoice"></i>
            <span>Kelola Invoice</span>
        </a>
        <div id="collapseInvoice" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/invoice">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Invoice Mitra
                </a>
            </div>
        </div>
    </li>

    <!-- Pengeluaran -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePengeluaran">
            <i class="fas fa-fw fa-money-bill-wave"></i>
            <span>Kelola Pengeluaran</span>
        </a>
        <div id="collapsePengeluaran" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/pengeluaran_internal">
                    <i class="fas fa-building me-2"></i> Internal
                </a>
                <a class="collapse-item" href="/pengeluaran_transport">
                    <i class="fas fa-truck-moving me-2"></i> Transport
                </a>
                <a class="collapse-item" href="/pengeluaran_pajak">
                    <i class="fas fa-receipt me-2"></i> Pajak Mobil
                </a>
            </div>
        </div>
    </li>

    <!-- Pemasukan -->
    <li class="nav-item">
        <a class="nav-link collapsed pemasukan" href="#" data-toggle="collapse" data-target="#collapsePemasukan">
            <i class="fas fa-fw fa-coins"></i>
            <span>Kelola Pemasukan</span>
        </a>
        <div id="collapsePemasukan" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/pemasukan">
                    <i class="fas fa-wallet me-2"></i> Pemasukan Harian
                </a>
                <a class="collapse-item" href="/pemasukan-laporan-bulanan">
                    <i class="fas fa-chart-line me-2"></i> Laporan Bulanan
                </a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Lainnya</div>

    <li class="nav-item">
        <a class="nav-link" href="/contact">
            <i class="fas fa-fw fa-envelope"></i>
            <span>Pesan</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="/calon-mitra">
            <i class="fas fa-fw fa-user-plus"></i>
            <span>Calon Mitra</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

</ul>
<!-- End Sidebar -->
