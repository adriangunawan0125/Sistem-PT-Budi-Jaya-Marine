<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Sistem</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-4">

            <div class="card shadow border-0">
                <div class="card-body p-4">

                    <!-- LOGO PERUSAHAAN -->
                    <div class="text-center mb-3">
                        <!-- OPSI ICON (sementara) 
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width:90px;height:90px;">
                            <i class="bi bi-building fs-1"></i>
                        </div>-->

                        
                        <img src="{{ asset('assets/logo.png') }}" width="150" alt="Logo Perusahaan">
                    
                    </div>

                    <!-- JUDUL -->
                    <h4 class="text-center fw-bold mb-4 text-dark">
                        LOGIN
                    </h4>

                    <!-- ERROR -->
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- FORM LOGIN -->
                    <form action="{{ route('login.process') }}" method="POST">
                        @csrf

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope-fill"></i>
                                </span>
                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       placeholder="email@example.com"
                                       required>
                            </div>
                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password"
                                       name="password"
                                       class="form-control"
                                       placeholder="********"
                                       required>
                            </div>
                        </div>

                        <!-- BUTTON -->
                        <button type="submit" class="btn btn-primary w-100 fw-semibold">
                            Login
                        </button>
                    </form>

                </div>
            </div>

            <p class="text-center text-white mt-3 small">
                © {{ date('Y') }} BudiJaya Marine
            </p>

        </div>
    </div>
</div>

</body>
</html>
