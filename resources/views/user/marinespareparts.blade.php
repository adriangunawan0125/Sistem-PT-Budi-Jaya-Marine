@extends('user.layouts.app')

@section('content')
<style>
/* ==========================
   ANIMASI CONTENT MARINE SPARE PARTS
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
.animate-section h4,
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
.animate-section.animate h4,
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
    background:
    linear-gradient(
        to bottom,
        rgba(5,10,48,.83),
        rgba(5,10,48,.43)
    ),
    url('{{ asset('assets/hero1.jpg') }}') center/cover no-repeat;
    padding: 160px 0 90px;
">
    <div class="container text-center">
        <h1 class="fw-bold mb-3">
    <i class="fa fa-screwdriver-wrench me-3 text-primary"></i>Marine Spare Parts
</h1>

        <p class="fs-5 opacity-90">
            Penyedia Perlengkapan & Spare Parts Kapal
        </p>
    </div>
</section>

<!-- INTRO -->
<section class="py-5 bg-light animate-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <p class="fs-5 text-muted">
                    PT Budi Jaya Marine hadir untuk membantu Anda dalam memenuhi
                    kebutuhan perlengkapan maupun <strong>spare parts kapal</strong>.
                    Kami berfokus pada kualitas produk dan pelayanan sehingga
                    kepuasan customer menjadi prioritas utama kami.
                </p>
            </div>
        </div>
    </div>
</section>
<!-- CONTENT -->
<section class="py-5 animate-section">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- IMAGE -->
            <div class="col-lg-6 text-center">
                <img src="{{ asset('assets/ship-engine-parts-02.jpg') }}"
                     class="img-fluid rounded shadow"
                     alt="Marine Spare Parts">
            </div>

            <!-- LIST WITH ICON -->
            <div class="col-lg-6">
                <h4 class="fw-bold mb-4">
                    Produk & Layanan Marine Spare Parts
                </h4>

                <ul class="list-unstyled">

                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-gear-fill text-primary fs-4 me-3"></i>
                        <span>
                            <strong>Supply Stern Tube Seal & Stern Tube Bearings</strong>
                        </span>
                    </li>


                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-tools text-primary fs-4 me-3"></i>
                        <span>
                            <strong>Engine Components, Ship Spare Parts & Deck Equipments</strong>
                        </span>
                    </li>

                    <!-- Tambahan Spare Parts Baru -->
                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-wrench text-primary fs-4 me-3"></i>
                        <span>
                            <strong>Engine & Machinery Spare Parts</strong>
                        </span>
                    </li>

                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-funnel text-primary fs-4 me-3"></i>
                        <span>
                            <strong>Pump Valve & Pipe Fitting</strong>
                        </span>
                    </li>

                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-life-preserver text-primary fs-4 me-3"></i>
                        <span>
                            <strong>Deck & Mooring Equipment</strong>
                        </span>
                    </li>

                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-lightning-fill text-primary fs-4 me-3"></i>
                        <span>
                            <strong>Electrical & Automation</strong>
                        </span>
                    </li>

                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-shield-exclamation text-primary fs-4 me-3"></i>
                        <span>
                            <strong>Safety & Life Saving Equipment</strong>
                        </span>
                    </li>

                    <li class="d-flex align-items-start">
                        <i class="bi bi-box-seam text-primary fs-4 me-3"></i>
                        <span>
                            <strong>Consumable & Maintenance</strong>
                        </span>
                    </li>

                </ul>
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
                                    Butuh Marine Spare Parts?
                                </h4>
                                <p class="text-white-50 mb-0">
                                   Tim kami siap membantu Anda dengan produk berkualitas dan pelayanan profesional.
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
