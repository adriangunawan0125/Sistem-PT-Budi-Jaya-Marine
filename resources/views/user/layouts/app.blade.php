<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Budi Jaya Marine')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- FONT --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- BOOTSTRAP --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- ICON --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    {{-- GLOBAL STYLE --}}
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
        }

        /* HERO */
        .hero {
            height: 100vh;
            background:
                linear-gradient(rgba(5,10,48,.65), rgba(5,10,48,.65)),
                url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee') center/cover;
            color: #fff;
            display: flex;
            align-items: center;
        }

        .hero h1 {
            font-weight: 700;
            font-size: 42px;
        }

        /* SERVICE */
        .service-section {
            background: #4f6ef7;
        }

        .service-wrapper {
            border-radius: 30px;
            padding: 60px 40px;
            box-shadow: 0 25px 60px rgba(0,0,0,.15);
        }

        .service-item {
            padding: 30px 20px;
            transition: all .3s ease;
        }

        .service-item:hover {
            transform: translateY(-10px);
        }

        .service-icon {
            width: 90px;
            height: 90px;
            background: #f4f6ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
        }

        .service-icon i {
            font-size: 42px;
            color: #4f6ef7;
        }

        .service-item p {
            font-size: 15px;
            color: #555;
            margin-top: 10px;
        }
    </style>

    {{-- PAGE SPECIFIC STYLE (WAJIB DI HEAD) --}}
    @stack('style')
</head>

<body>

    {{-- NAVBAR --}}
    @include('user.partials.navbar')

    {{-- MAIN --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('user.partials.footer')

    {{-- CORE SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- NAVBAR SCROLL --}}
    <script>
        const navbar = document.querySelector('.navbar-custom');
        const topBar = document.querySelector('.top-bar');

        if (navbar && topBar) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 60) {
                    navbar.classList.add('scrolled');
                    topBar.style.transform = 'translateY(-100%)';
                } else {
                    navbar.classList.remove('scrolled');
                    topBar.style.transform = 'translateY(0)';
                }
            });
        }
    </script>

    {{-- PAGE SPECIFIC SCRIPT (PALING BAWAH) --}}
    @stack('script')

</body>
</html>
