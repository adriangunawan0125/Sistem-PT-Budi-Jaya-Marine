<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sistem BJM</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('/')}}sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('/')}}sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="{{asset('/')}}sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<style>
    .dashboard-card {
    border: none;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.dashboard-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df, #224abe);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #1cc88a, #0f9d58);
}

.bg-gradient-danger {
    background: linear-gradient(45deg, #e74a3b, #be2617);
}

.bg-gradient-warning {
    background: linear-gradient(45deg, #f6c23e, #dda20a);
}

.bg-gradient-info {
    background: linear-gradient(45deg, #36b9cc, #258391);
}

.icon-circle {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-circle i {
    font-size: 22px;
}

</style>

</head>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<body id="page-top">

    <div id="wrapper">

    <!-- SIDEBAR (DINAMIS BERDASARKAN ROLE) -->
    @include('components.sidebars.' . auth()->user()->role)

    <!-- CONTENT WRAPPER -->
    <div id="content-wrapper" class="d-flex flex-column">

    <!-- MAIN CONTENT -->
<div id="content" style="padding-top:40px;">  <!-- <--- ini aja -->
    <!-- TOPBAR -->
    @includeIf('components.topbars.' . auth()->user()->role)

    <!-- PAGE CONTENT -->
    <div class="container-fluid">
        @yield('content')
    </div>
</div>


        <!-- FOOTER -->
        <x-footer />

    </div>

</div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('/')}}sbadmin2/vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('/')}}sbadmin2vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('/')}}sbadmin2js/sb-admin-2.min.js"></script>
    <!-- Bootstrap core JavaScript-->
<script src="{{ asset('sbadmin2/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('sbadmin2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('sbadmin2/js/sb-admin-2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>