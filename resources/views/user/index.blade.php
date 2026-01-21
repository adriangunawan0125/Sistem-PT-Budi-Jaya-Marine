@extends('user.layouts.app')

@section('title','Home')

@section('content')
<style>.hero {
    position: relative;
}

.hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.45); /* tingkat gelap */
    z-index: 1;
}

.hero .container {
    position: relative;
    z-index: 2;
}
</style>
</section>
<!-- HERO CAROUSEL -->
<section class="hero-carousel">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

        <div class="carousel-inner">

          <!-- SLIDE 1 -->
<div class="carousel-item active">
    <div class="hero d-flex align-items-center" 
         style="
             background-image: url('assets/hero1.jpg');
             background-size: cover;
             background-position: center;
             background-repeat: no-repeat;
             min-height: 600px;
         ">
        <div class="container">
            <div class="col-md-6 text-white">
                <h1>Selamat Datang di<br>Budi Jaya Marine</h1>
                <p>
                    Terima kasih telah mengunjungi website kami.
                    Kami hadir untuk memudahkan kebutuhan bisnis Anda.
                </p>
                <a class="btn btn-mitra">
                    Hubungi Kami &nbsp; 0888-8888-8888
                </a>
            </div>
        </div>
    </div>
</div>

<!-- SLIDE 2 -->
<div class="carousel-item">
    <div class="hero d-flex align-items-center"
         style="
             background-image: url('assets/hero2.jpg');
             background-size: cover;
             background-position: center;
             background-repeat: no-repeat;
             min-height: 600px;
         ">
        <div class="container">
            <div class="col-md-6 text-white">
                <h1>Solusi Anda Yang bekerja <br>Profesional & Terpercaya</h1>
                <p>
                    Spesialis sparepart, transportasi, dan layanan marine
                    untuk perusahaan Anda.
                </p>
                <a class="btn btn-mitra">
                    Hubungi Kami &nbsp; 0888-8888-8888
                </a>
            </div>
        </div>
    </div>
</div>

<!-- SLIDE 3 -->
<div class="carousel-item">
    <div class="hero d-flex align-items-center"
         style="
             background-image: url('assets/hero3.jpg');
             background-size: cover;
             background-position: center;
             background-repeat: no-repeat;
             min-height: 600px;
         ">
        <div class="container">
            <div class="col-md-6 text-white">
                <h1>Mitra Anda<br>Yang Dapat Diandalkan</h1>
                <p>
                    Kami berkomitmen memberikan pelayanan terbaik
                    untuk setiap klien kami.
                </p>
                <a class="btn btn-mitra">
                    Hubungi Kami &nbsp; 0888-8888-8888
                </a>
            </div>
        </div>
    </div>
</div>

        </div>

        <!-- CONTROL -->
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
                <div class="service-item text-center">
                    <div class="service-icon">
                        <i class="fa fa-car"></i>
                    </div>
                    <h5 class="fw-bold mt-3">Transportation</h5>
                    <p>
                        Layanan jasa transportasi dengan kontrak
                        perusahaan yang profesional dan terpercaya.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-item text-center">
                    <div class="service-icon">
                        <i class="fa fa-ship"></i>
                    </div>
                    <h5 class="fw-bold mt-3">Marine Service</h5>
                    <p>
                        Jasa perbaikan kapal oleh tenaga ahli
                        profesional dan berpengalaman.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-item text-center">
                    <div class="service-icon">
                        <i class="fa fa-screwdriver-wrench"></i>
                    </div>
                    <h5 class="fw-bold mt-3">Marine Spareparts</h5>
                    <p>
                        Pengadaan suku cadang kapal dengan kualitas
                        terbaik dan pengiriman cepat.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

