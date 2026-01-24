<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-text mx-2">Owner Transport</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('owner.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Kelola</div>

    <!-- Laporan Pemasukan -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePemasukan"
            aria-expanded="true" aria-controls="collapsePemasukan">
            <i class="fas fa-fw fa-money-bill-wave"></i>
            <span>Laporan Pemasukan</span>
        </a>
        <div id="collapsePemasukan" class="collapse" aria-labelledby="headingPemasukan" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pemasukan :</h6>
                <a class="collapse-item" href="/laporan-pemasukan-harian">
                    <i class="fas fa-calendar-day me-1"></i> Hari Ini
                </a>
                <a class="collapse-item" href="/laporan-pemasukan-bulanan">
                    <i class="fas fa-calendar-alt me-1"></i> Bulan Ini
                </a>
            </div>
        </div>
    </li>

    <!-- Laporan Pengeluaran -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePengeluaran"
            aria-expanded="true" aria-controls="collapsePengeluaran">
            <i class="fas fa-fw fa-credit-card"></i>
            <span>Laporan Pengeluaran</span>
        </a>
        <div id="collapsePengeluaran" class="collapse" aria-labelledby="headingPengeluaran" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pengeluaran :</h6>
                <a class="collapse-item" href="owner.pengeluaran.internal">
                    <i class="fas fa-wallet me-1"></i> Internal
                </a>
                <a class="collapse-item" href="owner.pengeluaran.transport">
                    <i class="fas fa-truck me-1"></i> Transport
                </a>
                <a class="collapse-item" href="owner.pengeluaran.pajak">
                    <i class="fas fa-car me-1"></i> Pajak Mobil
                </a>
            </div>
        </div>
    </li>

    <!-- Mitra & Ex-Mitra -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMitra"
        aria-expanded="true" aria-controls="collapseMitra">
        <i class="fas fa-fw fa-users"></i>
        <span>Mitra & Ex-Mitra</span>
    </a>
    <div id="collapseMitra" class="collapse" aria-labelledby="headingMitra" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">daftar :</h6>
            <a class="collapse-item" href="owner.mitra.index">
                <i class="fas fa-user me-1"></i> Mitra
            </a>
            <a class="collapse-item" href="owner.ex_mitra.index">
                <i class="fas fa-user-slash me-1"></i> Ex-Mitra
            </a>
            <a class="collapse-item" href="owner.mitra.jaminan">
                <i class="fas fa-hand-holding-usd me-1"></i> Jaminan Mitra
            </a>
        </div>
    </div>
</li>


    <!-- Laporan Invoice -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInvoice"
            aria-expanded="true" aria-controls="collapseInvoice">
            <i class="fas fa-fw fa-file-invoice"></i>
            <span>Laporan Invoice</span>
        </a>
        <div id="collapseInvoice" class="collapse" aria-labelledby="headingInvoice" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">daftar :</h6>
                <a class="collapse-item" href="owner.invoice.lis">
                    <i class="fas fa-file-invoice-dollar me-1"></i> Invoice Mitra
                </a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

</ul>
<!-- End of Sidebar -->
