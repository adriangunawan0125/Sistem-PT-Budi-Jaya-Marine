@extends('user.layouts.app')

@section('title','Home')

@section('content')

<style>
/* ===== HERO OVERLAY ===== */
/* =========================
   HERO BASE
========================= */
.hero {
    position: relative;
    overflow: hidden;
}

.hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 1;
    opacity: 0;
}

/* BACKGROUND ZOOM */
.hero-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    transform: scale(1.08);
    transition: transform 6s ease;
}

.carousel-item.active .hero-bg {
    transform: scale(1);
}

/* OVERLAY FADE */
.carousel-item.active .hero::before {
    animation: overlayFade .8s ease forwards;
}

@keyframes overlayFade {
    to { opacity: 1; }
}

/* CONTENT */
.hero .container {
    position: relative;
    z-index: 2;
}

/* =========================
   TEXT ANIMATION
========================= */
.hero h1,
.hero p,
.hero .btn-mitra {
    opacity: 0;
    transform: translateY(30px);
}


.carousel-item.active .hero h1 {
    animation: fadeUp 1s ease forwards;
}
.carousel-item.active .hero p {
    animation: fadeUp 1s ease forwards;
    animation-delay: .2s;
}
.carousel-item.active .hero .btn-mitra {
    animation: fadeUp 1s ease forwards;
    animation-delay: .4s;
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(25px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== BUTTON WHATSAPP ===== */
.btn-mitra {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #203bc1;
    color: #fff;
    font-weight: 600;
    padding: 12px 22px;
    border-radius: 50px;
    text-decoration: none;
    transition: .3s ease;
}

.btn-mitra:hover {
    background: #15287f;
    color: #fff;
}

/* MOBILE FIX */
@media (max-width: 576px) {
    .hero h1 {
        font-size: 1.7rem;
    }
    .hero p {
        font-size: .95rem;
    }
    .btn-mitra {
        width: 100%;
        padding: 14px;
        font-size: .95rem;
    }
}

/* SERVICE ANIMATION */
.service-item {
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.8s ease-out;
}

.service-item.animate {
    opacity: 1;
    transform: translateY(0);
}

/* ICON ANIMATION */
.service-icon i {
    font-size: 50px;
    color: #203bc1;
    transition: transform 0.5s, color 0.5s;
}

.service-item:hover .service-icon i {
    transform: rotate(15deg) scale(1.2);
    color: #15287f;
}
</style>

<!-- HERO CAROUSEL -->
<section class="hero-carousel">
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

<div class="carousel-inner">

<!-- SLIDE 1 -->
<div class="carousel-item active">
    <div class="hero d-flex align-items-center"
         style="background:url('assets/hero1.jpg') center/cover no-repeat; min-height:600px;">
        <div class="container">
            <div class="col-md-6 text-white">
                <h1>Selamat Datang di<br>Budi Jaya Marine</h1>
                <p>
                    Terima kasih telah mengunjungi website kami.
                    Kami hadir untuk memudahkan kebutuhan bisnis Anda.
                </p>
                <a href="https://wa.me/6287770239693" target="_blank" class="btn btn-mitra">
                    <i class="fab fa-whatsapp"></i>
                  Hub 0877-7023-9693
                </a>
            </div>
        </div>
    </div>
</div>

<!-- SLIDE 2 -->
<div class="carousel-item">
    <div class="hero d-flex align-items-center"
         style="background:url('assets/wp12186590 (1).jpg') center/cover no-repeat; min-height:600px;">
        <div class="container">
            <div class="col-md-6 text-white">
                <h1>Solusi Profesional<br>& Terpercaya</h1>
                <p>
                    Spesialis sparepart, transportasi,
                    dan layanan marine perusahaan.
                </p>
                <a href="https://wa.me/62895385287940" target="_blank" class="btn btn-mitra">
                    <i class="fab fa-whatsapp"></i>
                   Hub 0895-3852-87940
                </a>
            </div>
        </div>
    </div>
</div>

<!-- SLIDE 3 -->
<div class="carousel-item">
    <div class="hero d-flex align-items-center"
         style="background:url('assets/2685253.jpg') center/cover no-repeat; min-height:600px;">
        <div class="container">
            <div class="col-md-6 text-white">
                <h1>Mitra Anda<br>Yang Dapat Diandalkan</h1>
                <p>
                    Kami berkomitmen memberikan pelayanan
                    terbaik untuk setiap klien.
                </p>
                <a href="https://wa.me/6287770239693" target="_blank" class="btn btn-mitra">
                    <i class="fab fa-whatsapp"></i>
                   Hub 0877-7023-9693
                </a>
            </div>
        </div>
    </div>
</div>

</div>

<button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
</button>
<button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
</button>

</div>
</section>
<!-- SERVICE -->
<section class="service-section py-5">
  <div class="container">
    <div class="row bg-white service-wrapper">

      <div class="col-md-4">
        <div class="service-item text-center animate-on-scroll">
          <div class="service-icon"><i class="fa fa-car"></i></div>
          <h5 class="service-text">Transportation</h5>
          <p class="service-text">Layanan transportasi profesional & terpercaya.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="service-item text-center animate-on-scroll">
          <div class="service-icon"><i class="fa fa-ship"></i></div>
          <h5 class="service-text">Marine Service</h5>
          <p class="service-text">Perbaikan kapal oleh tenaga ahli berpengalaman.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="service-item text-center animate-on-scroll">
          <div class="service-icon"><i class="fa fa-screwdriver-wrench"></i></div>
          <h5 class="service-text">Marine Spareparts</h5>
          <p class="service-text">Pengadaan sparepart kapal berkualitas tinggi.</p>
        </div>
      </div>

    </div>
  </div>
</section>

<style>
/* SERVICE ANIMATION */
.service-item {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease-out;
}

.service-item.animate {
    opacity: 1;
    transform: translateY(0);
}

/* TEXT ANIMATION */
.service-text {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.service-item.animate .service-text {
    opacity: 1;
    transform: translateY(0);
}

/* STAGGERED DELAY */
.service-item.animate .service-text:nth-child(2) {
    transition-delay: 0.2s; /* h5 */
}
.service-item.animate .service-text:nth-child(3) {
    transition-delay: 0.4s; /* p */
}

/* ICON ANIMATION */
.service-icon i {
    font-size: 50px;
    color: #203bc1;
    transition: transform 0.5s, color 0.5s;
}

.service-item:hover .service-icon i {
    transform: rotate(15deg) scale(1.2);
    color: #15287f;
}
</style>

<script>
// SIMPLE SCROLL ANIMATION
document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.animate-on-scroll');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting){
                entry.target.classList.add('animate');
            }
        });
    }, { threshold: 0.2 });

    items.forEach(item => observer.observe(item));
});
</script>

@endsection
