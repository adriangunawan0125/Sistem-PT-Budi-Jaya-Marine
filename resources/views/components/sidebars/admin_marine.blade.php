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

    <a class="sidebar-brand d-flex align-items-center justify-content-center py-4" href="/admin-transport">
        <div class="sidebar-brand-text">
            <div class="brand-title">ADMIN<br>TRANSPORT</div>
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link" href="/admin-transport">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Kelola</div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#mitraMarine">
            <i class="fas fa-ship"></i>
            <span>Kelola Mitra Marine</span>
        </a>
        <div id="mitraMarine" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">Menu 1</a>
                <a class="collapse-item" href="#">Menu 2</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#quotation">
            <i class="fas fa-file-signature"></i>
            <span>Kelola Quotation</span>
        </a>
        <div id="quotation" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">Menu 1</a>
                <a class="collapse-item" href="#">Menu 2</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#podo">
            <i class="fas fa-dolly"></i>
            <span>Kelola PO & DO</span>
        </a>
        <div id="podo" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">Menu 1</a>
                <a class="collapse-item" href="#">Menu 2</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#invoice">
            <i class="fas fa-file-invoice-dollar"></i>
            <span>Kelola Invoice</span>
        </a>
        <div id="invoice" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">Menu 1</a>
                <a class="collapse-item" href="#">Menu 2</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#soa">
            <i class="fas fa-file-alt"></i>
            <span>Kelola SOA</span>
        </a>
        <div id="soa" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">Menu 1</a>
                <a class="collapse-item" href="#">Menu 2</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#workingReport">
            <i class="fas fa-clipboard-list"></i>
            <span>Kelola Working Report</span>
        </a>
        <div id="workingReport" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">Menu 1</a>
                <a class="collapse-item" href="#">Menu 2</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#timesheet">
            <i class="fas fa-clock"></i>
            <span>Kelola Timesheet</span>
        </a>
        <div id="timesheet" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">Menu 1</a>
                <a class="collapse-item" href="#">Menu 2</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

</ul>
<!-- End Sidebar -->