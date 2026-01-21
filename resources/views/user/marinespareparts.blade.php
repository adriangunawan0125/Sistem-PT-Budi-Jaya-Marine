@extends('user.layouts.app')

@section('content')

<!-- HERO -->
<section class="text-white" style="
    background:
    linear-gradient(rgba(5,10,48,.85), rgba(5,10,48,.85)),
    url('{{ asset('assets/hero1.jpg') }}') center/cover no-repeat;
    padding: 150px 0 90px;
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
<section class="py-5 bg-light">
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
<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- IMAGE -->
            <div class="col-lg-6 text-center">
                <img src="{{ asset('assets/spare.jpg') }}"
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
                        <i class="bi bi-radar text-primary fs-4 me-3"></i>
                        <span>
                            <strong>Pengadaan Peralatan Navigasi & Komunikasi</strong><br>
                            Radar, GPS, AIS, Echo Sounder, Speed Log, Weather Fax
                        </span>
                    </li>

                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-compass-fill text-primary fs-4 me-3"></i>
                        <span>
                            VDR, Gyro Compass, Navtex, Radio VHF, Radio SSB
                        </span>
                    </li>

                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-shield-check text-primary fs-4 me-3"></i>
                        <span>
                            <strong>Pengadaan Perlengkapan Safety Kapal</strong>
                        </span>
                    </li>

                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-tools text-primary fs-4 me-3"></i>
                        <span>
                            <strong>Engine Components, Ship Spare Parts & Deck Equipments</strong>
                        </span>
                    </li>

                    <li class="d-flex align-items-start">
                        <i class="bi bi-box-seam text-primary fs-4 me-3"></i>
                        <span>
                            <strong>Marine Consumable Parts</strong>
                        </span>
                    </li>

                </ul>
            </div>

        </div>
    </div>
</section>

<<!-- CONTRACT CTA -->
<section class="py-5">
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
