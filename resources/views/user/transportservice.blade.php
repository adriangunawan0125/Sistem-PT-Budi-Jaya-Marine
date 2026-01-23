@extends('user.layouts.app')

@section('title', 'Transport Service')

@section('content')

<style>
/* ==========================
   ANIMASI CONTENT TRANSPORT SERVICE
=========================== */
.animate-section {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease-out;
}

.animate-section.animate {
    opacity: 1;
    transform: translateY(0);
}

.animate-section h1,
.animate-section h2,
.animate-section h3,
.animate-section h5,
.animate-section h6,
.animate-section p,
.animate-section li {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.8s ease-out;
}

.animate-section.animate h1,
.animate-section.animate h2,
.animate-section.animate h3,
.animate-section.animate h5,
.animate-section.animate h6,
.animate-section.animate p,
.animate-section.animate li {
    opacity: 1;
    transform: translateY(0);
}

/* Card fade + zoom */
.animate-section .card,
.animate-section .border {
    opacity: 0;
    transform: translateY(20px) scale(0.97);
    transition: all 0.6s ease-out;
}

.animate-section.animate .card,
.animate-section.animate .border {
    opacity: 1;
    transform: translateY(0) scale(1);
}

/* Hover card smooth */
.animate-section .card:hover,
.animate-section .border:hover {
    transform: scale(1.03);
    transition: transform 0.6s ease-out;
}

/* Icon zoom */
.animate-section .card i,
.animate-section .border i {
    transition: transform 0.6s ease-out, color 0.6s ease-out;
}

.animate-section .card:hover i,
.animate-section .border:hover i {
    transform: scale(1.15);
    color:#15287f;
}

/* Images */
.animate-section img,
.animate-section iframe {
    opacity: 0;
    transform: translateY(20px) scale(0.97);
    transition: all 0.8s ease-out;
}

.animate-section.animate img,
.animate-section.animate iframe {
    opacity: 1;
    transform: translateY(0) scale(1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sections = document.querySelectorAll('.animate-section');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting){
                entry.target.classList.add('animate');
            }
        });
    }, { threshold: 0.2 });
    sections.forEach(section => observer.observe(section));
});
</script>

<!-- HERO -->
<section class="text-white animate-section" style="
    background: linear-gradient(rgba(5,10,48,.8), rgba(5,10,48,.8)),
    url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee') center/cover no-repeat;
    padding: 160px 0 90px;
">
    <div class="container text-center">
        <h1 class="fw-bold mb-3">
    <i class="fa fa-car-side me-3 text-primary"></i>Transport Service
</h1>


        <p class="mb-0" style="opacity:.85;">
            Home / <span class="text-primary">Transport Service</span>
        </p>
    </div>
</section>

<!-- INTRO -->
<section class="py-5 animate-section">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- IMAGE -->
            <div class="col-lg-5 text-center">
                <img 
                    src="assets\bg-transport.jpg"
                    class="img-fluid rounded shadow-sm"
                    alt="Layanan Transportasi PT Budi Jaya Marine">
            </div>

            <!-- TEXT -->
            <div class="col-lg-7">
                <h3 class="fw-bold mb-4">
                    Layanan Transportasi Berbasis Kontrak Perusahaan
                </h3>

                <p class="text-muted fs-5">
                    PT Budi Jaya Marine menyediakan layanan transportasi berbasis
                    kontrak, baik untuk kebutuhan
                    <strong>jangka menengah (6 bulanan)</strong> maupun
                    <strong>jangka panjang (tahunan)</strong>.
                </p>

                <p class="text-muted fs-5">
                    Layanan ini ditujukan bagi <strong>perorangan, </strong><strong>perusahaan swasta</strong>,
                    <strong>instansi pemerintahan</strong>, serta
                    <strong>perusahaan BUMN</strong> yang membutuhkan solusi
                    transportasi profesional dan terintegrasi.
                </p>
            </div>

        </div>
    </div>
</section>


<!-- VALUE -->
<section class="py-5 bg-light animate-section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <h4 class="fw-bold mb-4">
                    Fokuskan Bisnis Anda, Biarkan Transportasi Kami Kelola
                </h4>

                <p class="text-muted fs-6">
                    Percayakan pengelolaan kebutuhan transportasi perusahaan Anda
                    kepada kami, sehingga Anda dapat lebih fokus pada
                    <strong>pengembangan dan aktivitas inti bisnis</strong>
                    tanpa terbebani urusan operasional kendaraan.
                </p>

                <p class="text-muted fs-6">
                    Sebagai penyedia jasa transportasi profesional,
                    <strong>PT Budi Jaya Marine</strong> berkomitmen memberikan
                    layanan yang <strong>aman</strong>, <strong>nyaman</strong>,
                    dan <strong>andal</strong> untuk mendukung mobilitas perusahaan
                    secara optimal.
                </p>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-3">Keunggulan Layanan Kami</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Kontrak fleksibel 6 bulanan dan tahunan
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Armada terawat dan siap operasional
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Pengelolaan transportasi terintegrasi
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Dukungan operasional untuk perusahaan skala besar
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- OPERATIONAL -->
<section class="py-5 animate-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h4 class="fw-bold mb-4 text-center">
                    Efisiensi Biaya & Pengelolaan Kendaraan
                </h4>

                <p class="text-muted fs-6 text-center">
                    Layanan kami membantu perusahaan dalam
                    <strong>menekan biaya operasional</strong>
                    melalui pengelolaan kendaraan yang terintegrasi,
                    mencakup:
                </p>

                <div class="row mt-4">
                    <div class="col-md-4 text-center mb-3">
                        <div class="border rounded p-4 h-100">
                            <i class="bi bi-clipboard-check fs-2 text-primary mb-2"></i>
                            <h6 class="fw-bold">Inspeksi Rutin</h6>
                            <p class="text-muted mb-0">
                                Pemeriksaan kendaraan secara berkala
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4 text-center mb-3">
                        <div class="border rounded p-4 h-100">
                            <i class="bi bi-tools fs-2 text-primary mb-2"></i>
                            <h6 class="fw-bold">Servis Berkala</h6>
                            <p class="text-muted mb-0">
                                Perawatan sesuai standar operasional
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4 text-center mb-3">
                        <div class="border rounded p-4 h-100">
                            <i class="bi bi-shield-check fs-2 text-primary mb-2"></i>
                            <h6 class="fw-bold">Keamanan Kendaraan</h6>
                            <p class="text-muted mb-0">
                                Kendaraan siap pakai dan aman digunakan
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- CONTRACT CTA -->
<section class="py-5 animate-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="card border-0 shadow-lg"
                     style="background:linear-gradient(135deg,#050A30,#0A1A55);">
                    <div class="card-body p-5">
                        <div class="row align-items-center g-4">

                            <!-- ICON -->
                            <div class="col-md-2 text-center">
                                <div class="bg-primary bg-opacity-25 text-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                                     style="width:72px;height:72px;">
                                    <i class="bi bi-truck fs-2"></i>
                                </div>
                            </div>

                            <!-- TEXT -->
                            <div class="col-md-7">
                                <h4 class="fw-bold text-white mb-2">
                                    Transport Service
                                </h4>
                                <p class="text-white-50 mb-0">
                                    PT Budi Jaya Marine menyediakan layanan transportasi
                                    yang andal, aman, dan tepat waktu untuk mendukung
                                    kebutuhan operasional proyek dan distribusi pelanggan
                                    kami.
                                </p>
                            </div>

                            <!-- CTA -->
                            <div class="col-md-3 text-md-end text-center">
                                <a href="/hubungi-kami" class="btn btn-primary px-4 py-2">
                                    <i class="bi bi-telephone me-2"></i>
                                    Hubungi Kami
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


@endsection
