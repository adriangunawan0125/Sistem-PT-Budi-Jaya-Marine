 
 
 
 <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
    <div class="sidebar-brand-icon">
        <img src="{{ asset('assets/logo.png') }}"
             alt="Logo Perusahaan"
             width="35">
    </div>
    <div class="sidebar-brand-text mx-2">
        ADMIN TRANSPORT
    </div>
</a>



            
            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="/admin-transport">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Kelola
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
           <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Kelola Mitra</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">kelola</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li>-->
            
             <!-- ===================== -->
    <!-- KELOLA UNIT -->
    <!-- ===================== -->
    <li class="nav-item {{ request()->is('admin-transport/unit*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse"
           data-target="#collapseUnit"
           aria-expanded="{{ request()->is('admin-transport/unit*') ? 'true' : 'false' }}"
           aria-controls="collapseUnit">
            <i class="fas fa-fw fa-car"></i>
            <span>Kelola Unit</span>
        </a>

        <div id="collapseUnit"
             class="collapse {{ request()->is('admin-transport/unit*') ? 'show' : '' }}"
             data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Kelola Unit:</h6>
                <a class="collapse-item {{ request()->is('admin-transport/unit') ? 'active' : '' }}"
                   href="/admin-transport/unit">
                    Daftar Unit
                </a>
                <a class="collapse-item {{ request()->is('admin-transport/unit/create') ? 'active' : '' }}"
                   href="/admin-transport/unit/create">
                    Tambah Unit
                </a>
            </div>
        </div>
    </li>

    <!-- ===================== -->
    <!-- KELOLA MITRA -->
    <!-- ===================== -->
    <li class="nav-item {{ request()->is('admin-transport/mitra*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse"
           data-target="#collapseMitra"
           aria-expanded="{{ request()->is('admin-transport/mitra*') ? 'true' : 'false' }}"
           aria-controls="collapseMitra">
            <i class="fas fa-fw fa-users"></i>
            <span>Kelola Mitra</span>
        </a>

        <div id="collapseMitra"
             class="collapse {{ request()->is('admin-transport/mitra*') ? 'show' : '' }}"
             data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Kelola Mitra:</h6>
                <a class="collapse-item {{ request()->is('admin-transport/mitra') ? 'active' : '' }}"
                   href="/admin-transport/mitra">
                    Daftar Mitra
                </a>
                <a class="collapse-item {{ request()->is('admin-transport/mitra/create') ? 'active' : '' }}"
                   href="/admin-transport/mitra/create">
                    Tambah Mitra
                </a>
                <a class="collapse-item {{ request()->is('admin-transport/jaminan/mitra') ? 'active' : '' }}"
                   href="/admin-transport/jaminan/mitra">
                    Data Jaminan Mitra
                </a>
                <a class="collapse-item {{ request()->is('admin-transport/laporan/mitra') ? 'active' : '' }}"
                   href="/admin-transport/laporan/mitra">
                    Laporan Jumlah Mitra
                </a>
            </div>
        </div>
    </li>

           <!-- Kelola Invoice -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse"
       data-target="#collapseInvoice"
       aria-expanded="false"
       aria-controls="collapseInvoice">
        <i class="fas fa-fw fa-file-invoice"></i>
        <span>Kelola Invoice</span>
    </a>
    <div id="collapseInvoice" class="collapse" aria-labelledby="headingInvoice"
         data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Kelola</h6>
            <a class="collapse-item" href="/invoice">Invoice</a>
            <a class="collapse-item" href="utilities-animation.html">Cetak Invoice</a>
        </div>
    </div>
</li>

<!-- Kelola Pengeluaran -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse"
       data-target="#collapsePengeluaran"
       aria-expanded="false"
       aria-controls="collapsePengeluaran">
        <i class="fas fa-fw fa-money-bill-wave"></i>
        <span>Kelola Pengeluaran</span>
    </a>
    <div id="collapsePengeluaran" class="collapse" aria-labelledby="headingPengeluaran"
         data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Kelola</h6>
            <a class="collapse-item" href="/pengeluaran_internal">Pengeluaran Internal</a>
            <a class="collapse-item" href="/pengeluaran_transport">Pengeluaran Transport</a>
            <a class="collapse-item" href="/pengeluaran_pajak">Pengeluaran Pajak Mobil</a>
            <a class="collapse-item" href="utilities-animation.html">Laporan Pengeluaran</a>
        </div>
    </div>
</li>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>

            <!-- Nav Item - Pages Collapse Menu
            <li class="nav-item active">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
                    aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pages</span>
                </a>
                <div id="collapsePages" class="collapse show" aria-labelledby="headingPages"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="login.html">Login</a>
                        <a class="collapse-item" href="register.html">Register</a>
                        <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Other Pages:</h6>
                        <a class="collapse-item" href="404.html">404 Page</a>
                        <a class="collapse-item active" href="blank.html">Blank Page</a>
                    </div>
                </div>
            </li>  -->

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) 
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>-->

        </ul>
        <!-- End of Sidebar -->