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
        /* Background */
        body {
            min-height: 100vh;
            background:
                linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)),
                url("{{ asset('assets/hero1.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Card NORMAL (tidak blur) */
        .card {
            background: rgba(255, 255, 255, 0.92);
            border-radius: 12px;
            border: none;
            color: #333;
        }

        /* Input normal */
        .form-control {
            background: #fff;
            border: 1px solid #ced4da;
            color: #333;
        }

        .form-control::placeholder {
            color: #6c757d;
        }

        .form-control:focus {
            background: #fff;
            border-color: #86b7fe;
            color: #333;
            box-shadow: 0 0 0 .15rem rgba(13,110,253,.25);
        }

        .input-group-text {
            background: #f1f3f5;
            border: 1px solid #ced4da;
            color: #495057;
        }

        label {
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-4">

            <div class="card shadow">
                <div class="card-body p-4">

                    <!-- LOGO -->
                    <div class="text-center mb-3">
                        <img src="{{ asset('assets/logo.png') }}" width="150" alt="Logo Perusahaan">
                    </div>

                    <!-- JUDUL -->
                    <h4 class="text-center fw-bold mb-4">
                        LOGIN
                    </h4>

                    <!-- ERROR -->
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- FORM -->
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

                    <div class="mt-3 text-center">
                        <a href="/" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>

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
