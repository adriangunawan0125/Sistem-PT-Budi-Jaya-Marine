@extends('user.layouts.app')

@section('title', 'Marine Service')

@section('content')

<style>
/* ==========================
   ANIMASI CONTENT MARINE SERVICE
   - HERO/BANNER TIDAK ANIMASI
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
.animate-section.animate p,
.animate-section.animate li {
    opacity: 1;
    transform: translateY(0);
}

/* Card fade + zoom */
.animate-section .card {
    opacity: 0;
    transform: translateY(20px) scale(0.97);
    transition: all 0.6s ease-out;
}

.animate-section.animate .card {
    opacity: 1;
    transform: translateY(0) scale(1);
}

/* Hover card smooth */
.animate-section .card:hover {
    transform: scale(1.03);
    transition: transform 0.6s ease-out;
}

/* Icon di card hanya zoom */
.animate-section .card i {
    transition: transform 0.6s ease-out, color 0.6s ease-out;
}

.animate-section .card:hover i {
    transform: scale(1.15);
    color:#15287f;
}

/* Image atau iframe */
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
    background: linear-gradient(rgba(5,10,48,.85), rgba(5,10,48,.85)),
    url('assets/hero1.jpg') center/cover no-repeat;
    padding: 160px 0 90px;
">
    <div class="container text-center">
        <h1 class="fw-bold mb-3" style="font-size:46px;">
            <i class="bi bi-water text-primary me-2"></i>Marine Service
        </h1>
        <p class="mb-0" style="opacity:.85;">
            Home / <span class="text-primary">Marine Service</span>
        </p>
    </div>
</section>

<!-- INTRO -->
<section class="py-5 animate-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 text-center">
                <img src="{{ asset('assets\service-1536x864.jpeg') }}"
                     class="img-fluid rounded shadow-sm"
                     alt="Marine Service PT Budi Jaya Marine">
            </div>
            <div class="col-lg-7">
                <h3 class="fw-bold mb-4">
                    <i class="bi bi-tools text-primary me-2"></i>
                    Jasa Pemeliharaan & Perbaikan Kapal
                </h3>

                <p class="text-muted">
                    PT Budi Jaya Marine berkomitmen memberikan jasa pemeliharaan serta
                    perbaikan kapal dengan tenaga ahli berkompeten dan berpengalaman
                    dalam pekerjaan pemeliharaan dan perbaikan kapal.
                </p>

                <p class="text-muted">
                    Fokus utama kami adalah membantu pelanggan kami untuk troubleshooting
                    dan perbaikan kapal di dok kering, bengkel maupun floating repair.
                </p>

                <p class="text-muted mb-0">
                    Kami juga dapat melayani kontrak services per semester atau tahunan,
                    sehingga memberi kemudahan kepada pelanggan kami dalam maintenance
                    rutin yang dikerjakan oleh team professional kami.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- SISTEM PROPULSI -->
<section class="py-5 bg-light animate-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold border-bottom pb-2 mb-4">
                            <i class="bi bi-gear-wide-connected text-primary me-2"></i>
                            Sistem Propulsi & Kemudi
                        </h5>
                        <ul class="list-unstyled mb-0">
                            <li class="border-bottom py-2">
                                <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                Pemeliharaan Propeller & Shaft Propeller
                            </li>
                            <li class="border-bottom py-2">
                                <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                Pemeliharaan Tongkat Kemudi & Daun Kemudi
                            </li>
                            <li class="border-bottom py-2">
                                <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                Perbaikan / Penggantian Stern Tube Seal & Shaft Bearing
                            </li>
                            <li class="py-2">
                                <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                Inspeksi / Repair Rudder Stock Bearing
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="{{ asset('assets\propulsion1.png') }}"
                     class="img-fluid rounded shadow-sm"
                     alt="Sistem Propulsi Kapal">
            </div>
        </div>
    </div>
</section>

<!-- ENGINE -->
<section class="py-5 animate-section">
    <div class="container">
        <div class="row align-items-center g-5 flex-lg-row-reverse">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold border-bottom pb-2 mb-4">
                            <i class="bi bi-speedometer2 text-primary me-2"></i>
                            Auxiliary & Main Engine Services
                        </h5>
                        <ul class="list-unstyled mb-0">
                            <li class="border-bottom py-2">
                                <i class="bi bi-wrench-adjustable-circle text-primary me-2"></i>
                                Maintenance
                            </li>
                            <li class="border-bottom py-2">
                                <i class="bi bi-arrow-repeat text-primary me-2"></i>
                                Top Overhaul
                            </li>
                            <li class="py-2">
                                <i class="bi bi-gear-fill text-primary me-2"></i>
                                General Overhaul
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="{{ asset('assets\WhatsApp-Image-2023-06-30-at-23.57.39-1536x1152.jpeg') }}"
                     class="img-fluid rounded shadow-sm"
                     alt="Engine Services Kapal">
            </div>
        </div>
    </div>
</section>

<!-- NAVIGATION -->
<section class="py-5 bg-light animate-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold border-bottom pb-2 mb-4">
                            <i class="bi bi-compass text-primary me-2"></i>
                            Navigasi & Komunikasi
                        </h5>

                        <p class="text-muted">
                            Team handal kami siap datang ke kapal untuk melakukan
                            perbaikan atau maintenance peralatan navigasi dan komunikasi.
                        </p>

                        <ul class="list-unstyled mb-0">
                            <li class="border-bottom py-2">
                                <i class="bi bi-broadcast text-primary me-2"></i>
                                RADAR, GPS, AIS, Echo Sounder
                            </li>
                            <li class="border-bottom py-2">
                                <i class="bi bi-wifi text-primary me-2"></i>
                                Fish Finder, Speed Log, Navtex, Weather Fax
                            </li>
                            <li class="border-bottom py-2">
                                <i class="bi bi-radio text-primary me-2"></i>
                                VDR, ECDIS, Radio VHF, Radio SSB
                            </li>
                            <li class="py-2">
                                <i class="bi bi-plus-circle text-primary me-2"></i>
                                Peralatan Navigasi & Komunikasi Lainnya
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="{{ asset('assets\Radar.png') }}"
                     class="img-fluid rounded shadow-sm"
                     alt="Navigasi Kapal">
            </div>
        </div>
    </div>
</section>

<!-- SAFETY -->
<section class="py-5 animate-section">
    <div class="container">
        <div class="row align-items-center g-5 flex-lg-row-reverse">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold border-bottom pb-2 mb-4">
                            <i class="bi bi-shield-check text-primary me-2"></i>
                            Alat Keselamatan Pelayaran
                        </h5>

                        <p class="text-muted">
                            Annual Inspection, Services, Test and Renew Certificate
                        </p>

                        <ul class="list-unstyled mb-0">
                            <li class="border-bottom py-2">
                                <i class="bi bi-life-preserver text-primary me-2"></i>
                                Life Boat Davit & Winch System
                            </li>
                            <li class="border-bottom py-2">
                                <i class="bi bi-box text-primary me-2"></i>
                                Life Raft Services
                            </li>
                            <li class="border-bottom py-2">
                                <i class="bi bi-heart-pulse text-primary me-2"></i>
                                Life Saving Appliance
                            </li>
                            <li class="border-bottom py-2">
                                <i class="bi bi-fire text-primary me-2"></i>
                                Fire Fighting Equipment & System
                            </li>
                            <li class="py-2">
                                <i class="bi bi-arrow-up-right-circle text-primary me-2"></i>
                                Lifting Gear & Load Test
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="{{ asset('assets\life-raft.png') }}"
                     class="img-fluid rounded shadow-sm"
                     alt="Safety Equipment Kapal">
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
                                    <i class="bi bi-file-earmark-text fs-2"></i>
                                </div>
                            </div>

                            <!-- TEXT -->
                            <div class="col-md-7">
                                <h4 class="fw-bold text-white mb-2">
                                    Kontrak Service Berkala
                                </h4>
                                <p class="text-white-50 mb-0">
                                    Kami melayani kontrak service per semester maupun
                                    tahunan untuk memudahkan maintenance rutin kapal Anda
                                    agar lebih terjadwal, efisien, dan profesional.
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
