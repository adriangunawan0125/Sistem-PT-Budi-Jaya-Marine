<style>
/* ================= NAVBAR ================= */
.navbar-custom{
    position:fixed;
    top:44px;
    left:0;
    width:100%;
    padding:22px 0;
    background:transparent;
    z-index:1040;
    transition:all .3s ease;
}

.navbar-custom.scrolled{
    top:0;
    background:#050a30;
    box-shadow:0 4px 15px rgba(0,0,0,.4);
}

/* ================= BRAND / ICON (BESAR) ================= */
.navbar-brand{
    display:flex;
    align-items:center;
    gap:14px;
    text-decoration:none;
}

/* 🔥 LOGO DIBESARIN */
.navbar-brand img{
    height:62px;        /* ⬅️ BESAR */
    width:auto;
}

/* 🔥 TEKS IKUT NAIK */
.navbar-brand span{
    color:#fff;
    font-size:20px;
    font-weight:700;
    line-height:1.1;
    letter-spacing:.3px;
    white-space:nowrap;
}

/* ================= NAV LINK ================= */
.navbar-nav .nav-link{
    position:relative;
    font-weight:500;
    padding:8px 0;
}

.navbar-nav .nav-link::after{
    content:'';
    position:absolute;
    left:0;
    bottom:-6px;
    width:0;
    height:2px;
    background:#4f6ef7;
    transition:width .3s ease;
}

.navbar-nav .nav-link:hover::after,
.navbar-nav .nav-item.show > .nav-link::after{
    width:100%;
}

/* ================= BUTTON ================= */
.btn-mitra{
    background:#4f6ef7;
    color:#fff;
    padding:9px 24px;
    border-radius:25px;
    font-weight:500;
    transition:.3s;
    text-decoration:none;
}

.btn-mitra:hover{
    background:#3b56c2;
    color:#fff;
    transform:translateY(-2px);
    box-shadow:0 5px 15px rgba(0,0,0,.2);
}

/* ================= DROPDOWN ================= */
.dropdown-menu{
    margin-top:14px;
    background:rgba(5,10,48,.97);
    border:none;
    border-radius:14px;
    min-width:220px;
    padding:10px 0;
    box-shadow:0 20px 45px rgba(0,0,0,.35);
}

.dropdown-item{
    color:#fff;
    padding:12px 24px;
    font-weight:500;
}

.dropdown-item:hover{
    background:#4f6ef7;
    color:#fff;
}

/* ================= MOBILE ================= */
@media (max-width:991px){
    .navbar-custom{
        top:0;
        background:#050a30;
        padding:16px 0;
    }

    /* LOGO MOBILE */
    .navbar-brand img{
        height:50px;
    }

    .navbar-brand span{
        font-size:17px;
    }

    .dropdown-menu{
        position:static;
        box-shadow:none;
        background:#050a30;
        margin-top:8px;
        border-radius:10px;
    }

    .navbar-nav{
        padding-top:15px;
    }
}
</style>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom" id="mainNavbar">
    <div class="container">

        <!-- LOGO (BESAR & RAPI) -->
        <a class="navbar-brand" href="/">
            <img src="assets/logo.png" alt="BudiJaya Marine">
            <span>BudiJaya Marine</span>
        </a>

        <!-- TOGGLER -->
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto align-items-center gap-3">

                <li class="nav-item">
                    <a class="nav-link text-white" href="/">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" href="/tentang-kami">Tentang Kami</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown">
                        Layanan
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/transport-service">Transportation</a></li>
                        <li><a class="dropdown-item" href="/marine-service">Marine Service</a></li>
                        <li><a class="dropdown-item" href="/marine-spareparts">Marine Spareparts</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown">
                        Gallery
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/transport-gallery">Transportation</a></li>
                        <li><a class="dropdown-item" href="/service-gallery">Marine Service</a></li>
                        <li><a class="dropdown-item" href="/spareparts-gallery">Spareparts</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" href="/hubungi-kami">Hubungi Kami</a>
                </li>

                <li class="nav-item">
                    <a href="/daftar-mitra" class="btn btn-mitra ms-lg-3 mt-3 mt-lg-0">
                        daftar mitra transport
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<script>
const navbar = document.getElementById('mainNavbar');

window.addEventListener('scroll', () => {
    if (window.scrollY > 60) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});
</script>
