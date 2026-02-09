@extends('user.layouts.app')

@section('title', 'Hubungi Kami')

@section('content')

<style>
/* ==========================
   ANIMASI CONTENT (HUBUNGI KAMI)
=========================== */
.animate-section {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.9s ease-out;
}
.animate-section.animate { opacity: 1; transform: translateY(0); }

.animate-section h1,
.animate-section h2,
.animate-section h5,
.animate-section p {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.9s ease-out;
}
.animate-section.animate h1,
.animate-section.animate h2,
.animate-section.animate h5,
.animate-section.animate p {
    opacity: 1;
    transform: translateY(0);
}

.animate-section.animate h1 { transition-delay: 0.1s; }
.animate-section.animate h2 { transition-delay: 0.15s; }
.animate-section.animate h5 { transition-delay: 0.2s; }
.animate-section.animate p:nth-of-type(1) { transition-delay: 0.25s; }
.animate-section.animate p:nth-of-type(2) { transition-delay: 0.35s; }
.animate-section.animate p:nth-of-type(3) { transition-delay: 0.45s; }

.animate-section .card {
    opacity: 0;
    transform: translateY(20px) scale(0.97);
    transition: all 0.6s ease-out;
}
.animate-section.animate .card {
    opacity: 1;
    transform: translateY(0) scale(1);
}
.animate-section .card:hover { transform: scale(1.03); }
.animate-section .card i { transition: transform 0.6s ease-out, color 0.6s ease-out; }
.animate-section .card:hover i { transform: scale(1.15); color:#15287f; }

.animate-section iframe,
.animate-section img {
    opacity: 0;
    transform: translateY(20px) scale(0.97);
    transition: all 0.9s ease-out;
}
.animate-section.animate iframe,
.animate-section.animate img {
    opacity: 1;
    transform: translateY(0) scale(1);
}
/* CENTER SPINNER DI MODAL */
#loadingModal .modal-content{
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
    height:180px;
}

#loadingModal .spinner-border{
    display:block;
    margin:0 auto 15px auto;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const sections = document.querySelectorAll('.animate-section');
    const observerSection = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting){
                entry.target.classList.add('animate');
            }
        });
    }, { threshold: 0.2 });
    sections.forEach(section => observerSection.observe(section));

});
</script>

<!-- HERO -->
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
<section class="py-5 animate-section">
<div class="container">
<div class="row g-5">

<div class="col-lg-6">
<div class="card border shadow-sm">
<div class="card-body p-4">

<h5 class="fw-bold mb-3">Hubungi Kami</h5>
<p class="text-muted mb-4" style="font-size:14px;">
Silakan isi form di bawah ini, tim kami akan menghubungi Anda.
</p>

<form action="{{ route('contact.store') }}" method="POST" id="contactForm">
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

<div class="col-lg-6">
<h5 class="mb-3 text-center fw-bold">Lokasi PT Budi Jaya Marine</h5>
<div class="ratio ratio-16x9 shadow-sm rounded">
<iframe 
src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.550666288577!2d106.97603757362243!3d-6.190826793796762!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69892aeed596f5%3A0xe815e052357690dc!2sPT.%20Budi%20Jaya%20Marine!5e0!3m2!1sid!2sid!4v1768476013182!5m2!1sid!2sid"
style="border:0;" allowfullscreen loading="lazy"></iframe>
</div>
</div>

</div>
</div>
</section>

<!-- CONTACT INFO -->
<section class="py-5 bg-light animate-section">
<div class="container">
<h5 class="mb-5 text-center fw-bold">Atau hubungi kami melalui:</h5>
<div class="row justify-content-center g-4">

<div class="col-md-4">
<div class="card border-0 shadow-sm text-center p-4 h-100">
<i class="bi bi-envelope-fill fs-1 text-primary mb-3"></i>
<h6 class="fw-bold mb-1">Email</h6>
<p class="mb-0"><a href="mailto:budijayamarine@gmail.com" class="text-decoration-none">cs@budijayamarine.com</a></p>
</div>
</div>

<div class="col-md-4">
<div class="card border-0 shadow-sm text-center p-4 h-100">
<i class="bi bi-whatsapp fs-1 text-success mb-3"></i>
<h6 class="fw-bold mb-1">WhatsApp</h6>
<p class="mb-0">
<a href="https://wa.me/6287770239693" target="_blank" class="text-decoration-none">
+62 877-7023-9693
</a>
</p>
</div>
</div>

<div class="col-md-4">
<div class="card border-0 shadow-sm text-center p-4 h-100">
<i class="bi bi-geo-alt-fill fs-1 text-danger mb-3"></i>
<h6 class="fw-bold mb-1">Alamat</h6>
<p class="mb-0">Ruko Sentra Bisnis, Jl. Harapan Indah No.3 Blok SS 2, RT.3/RW.7, Pejuang, Kecamatan Medan Satria, Kota Bks, Jawa Barat 17132</p>
</div>
</div>

</div>
</div>
</section>

<!-- LOADING MODAL -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content text-center p-4">
<div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
<h5 class="mb-0">Mengirim pesan...</h5>
</div>
</div>
</div>

<!-- SUCCESS MODAL -->
<div class="modal fade" id="successModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content text-center p-4">
<div class="text-success mb-2" style="font-size:55px;">
<i class="fas fa-check-circle"></i>
</div>
<h5 class="mb-2">Pesan Berhasil Dikirim</h5>
<div>{{ session('success') }}</div>
<button class="btn btn-primary mt-3" data-bs-dismiss="modal">OK</button>
</div>
</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(){

const form = document.getElementById("contactForm");
if(form){
form.addEventListener("submit", function(){
let modal = new bootstrap.Modal(document.getElementById('loadingModal'));
modal.show();
});
}

@if(session('success'))
let successModal = new bootstrap.Modal(document.getElementById('successModal'));
successModal.show();
@endif

});
</script>

@endsection
