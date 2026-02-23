<style>
/* BRAND SIDEBAR */
.sidebar-brand {
    min-height: 90px;
}

.sidebar-brand-text .brand-title {
    font-size: 1.25rem;
    font-weight: 800;
    letter-spacing: 1px;
    line-height: 1.2;
}

/* MENU AKTIF */
.nav-item.active > .nav-link {
    font-weight: 700;
}

/* ICON PROPORSIONAL */
.nav-link i {
    font-size: 1rem;
    width: 20px;
}

/* FIXED SIDEBAR */
#accordionSidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    overflow-y: auto;
    width: 224px;
    z-index: 1030;
}

/* SCROLLBAR */
#accordionSidebar::-webkit-scrollbar {
    width: 6px;
}
#accordionSidebar::-webkit-scrollbar-thumb {
    background-color: rgba(255,255,255,.25);
    border-radius: 10px;
}

/* CONTENT SHIFT */
#content-wrapper {
    margin-left: 224px;
}

@media (max-width: 768px) {
    #accordionSidebar {
        position: relative;
        width: 100%;
        height: auto;
    }
    #content-wrapper {
        margin-left: 0;
    }
}
</style>


<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

   {{-- BRAND --}}
<a class="sidebar-brand d-flex align-items-center justify-content-center py-4"
   href="{{ route('admin.marine.dashboard') }}">

    <div class="text-center">
        <div class="fw-bold text-uppercase" style="font-size:14px; letter-spacing:1px;">
            Admin Marine
        </div>
        <small class="text-light">Management System</small>
    </div>

</a>

<hr class="sidebar-divider my-0">

{{-- DASHBOARD --}}
<li class="nav-item {{ request()->routeIs('admin.marine.dashboard') ? 'active' : '' }}">
    <a class="nav-link"
       href="{{ route('admin.marine.dashboard') }}">

        <i class="fas fa-chart-line me-2"></i>
        <span>Dashboard</span>

    </a>
</li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Kelola</div>

    {{-- QUOTATION --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#quotation">
            <i class="fas fa-fw fa-file-contract"></i>
            <span>Kelola Quotation</span>
        </a>
        <div id="quotation" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('quotations.index') }}">
                    Daftar Quotation
                </a>
            </div>
        </div>
    </li>

    {{-- PROJECT --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#project">
            <i class="fas fa-fw fa-project-diagram"></i>
            <span>Kelola Project</span>
        </a>
        <div id="project" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('po-masuk.index') }}">
                    PO dari Klien
                </a>
                <a class="collapse-item" href="{{ route('po-supplier.index') }}">
                    PO ke Supplier
                </a>
                <a class="collapse-item" href="{{ route('delivery-order.index') }}">
                    Delivery Order
                </a>
            </div>
        </div>
    </li>

    {{-- INVOICE --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#invoice">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Kelola Invoice</span>
        </a>
        <div id="invoice" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('invoice-po.index') }}">
                    Daftar Invoice
                </a>
            </div>
        </div>
    </li>

    {{-- SOA --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#soa">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Kelola SOA</span>
        </a>
        <div id="soa" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('soa.index') }}">
                    Daftar SOA
                </a>
            </div>
        </div>
    </li>

    {{-- WORKING REPORT --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#workingReport">
            <i class="fas fa-fw fa-clipboard-check"></i>
            <span>Working Report</span>
        </a>
        <div id="workingReport" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('working-report.index') }}">
                    Daftar Working Report
                </a>
            </div>
        </div>
    </li>

    {{-- TIMESHEET --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#timesheet">
            <i class="fas fa-fw fa-business-time"></i>
            <span>Timesheet</span>
        </a>
        <div id="timesheet" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('timesheet.index') }}">
                    Daftar Timesheet
                </a>
            </div>
        </div>
    </li>

    {{-- PENGELUARAN --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pengeluaran">
            <i class="fas fa-fw fa-money-bill-wave"></i>
            <span>Kelola Pengeluaran</span>
        </a>
        <div id="pengeluaran" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('pengeluaran-po.index') }}">
                    Daftar Pengeluaran
                </a>
            </div>
        </div>
    </li>

    {{-- PEMASUKAN --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pemasukan">
            <i class="fas fa-fw fa-wallet"></i>
            <span>Kelola Pemasukan</span>
        </a>
        <div id="pemasukan" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('pemasukan-marine.index') }}">
                    Daftar Pemasukan
                </a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

</ul>
