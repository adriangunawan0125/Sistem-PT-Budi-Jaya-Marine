@extends('user.layouts.app')

@section('title', 'Hubungi Kami')

@section('content')
<!-- HERO / BANNER -->
<section class="text-center text-white" style="
    background: linear-gradient(rgba(5,10,48,.75), rgba(5,10,48,.75)), url('assets/bgabout.jpg') center/cover no-repeat;
    padding: 150px 0 80px;
">
    <div class="container">
        <h1 class="fw-bold mb-3" style="font-size:48px;">Hubungi Kami</h1>
        <p class="mb-0" style="opacity:0.9;">Home / <span class="text-primary">Hubungi Kami</span></p>
    </div>
</section>

<!-- CONTACT FORM + MAP -->
<section class="py-5">
   <div class="container">
    <div class="row g-5">

        <!-- FORM -->
        <div class="col-lg-6">
            <div class="card border shadow-sm">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">
                        Hubungi Kami
                    </h5>
                    <p class="text-muted mb-4" style="font-size:14px;">
                        Silakan isi form di bawah ini, tim kami akan menghubungi Anda.
                    </p>

                   @if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<form action="{{ route('contact.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>No Telepon</label>
        <input type="text" name="no_telepon" class="form-control">
    </div>

    <div class="mb-3">
        <label>Pesan</label>
        <textarea name="pesan" rows="4" class="form-control" required></textarea>
    </div>

    <button class="btn btn-primary">Kirim Pesan</button>
</form>

                </div>
            </div>
        </div>


            <!-- Map -->
            <div class="col-lg-6">
                <h5 class="mb-3 text-center">Lokasi PT Budi Jaya Marine</h5>
                <div class="ratio ratio-16x9 shadow-sm rounded">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.550666288577!2d106.97603757362243!3d-6.190826793796762!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69892aeed596f5%3A0xe815e052357690dc!2sPT.%20Budi%20Jaya%20Marine!5e0!3m2!1sid!2sid!4v1768476013182!5m2!1sid!2sid" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTACT INFO -->
<section class="py-5 bg-light">
    <div class="container">
        <h5 class="mb-5 text-center fw-bold">Atau hubungi kami melalui:</h5>
        <div class="row justify-content-center g-4">
            <!-- Email -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center p-4 h-100">
                    <i class="bi bi-envelope-fill fs-1 text-primary mb-3"></i>
                    <h6 class="fw-bold mb-1">Email</h6>
                    <p class="mb-0"><a href="mailto:Budijayamarine@gmail.com" class="text-decoration-none">Budijayamarine@gmail.com</a></p>
                </div>
            </div>

            <!-- Telepon -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center p-4 h-100">
                    <i class="bi bi-telephone-fill fs-1 text-primary mb-3"></i>
                    <h6 class="fw-bold mb-1">Telepon</h6>
                    <p class="mb-0"><a href="tel:+6281234567890" class="text-decoration-none">+62 812-3456-7890</a></p>
                </div>
            </div>

            <!-- Alamat -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center p-4 h-100">
                    <i class="bi bi-geo-alt-fill fs-1 text-primary mb-3"></i>
                    <h6 class="fw-bold mb-1">Alamat</h6>
                    <p class="mb-0">Ruko Sentra Bisnis, Jl. Harapan Indah No.3 Blok SS 2, RT.3/RW.7, Pejuang, Kecamatan Medan Satria, Kota Bks, Jawa Barat 17132</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
