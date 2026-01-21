@extends('user.layouts.app')

@section('title', 'Gallery Transport')

@section('content')
@push('style')
<style>
/* ==========================
   ANIMASI CONTENT GALLERY
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
@endpush

@push('script')
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
@endpush

{{-- HERO --}}
<section class="text-white animate-section" style="
    background: linear-gradient(rgba(5,10,48,.85), rgba(5,10,48,.85)),
    url('{{ asset('assets/bg-transport.jpg') }}') center/cover no-repeat;
    padding: 160px 0 90px;
">
    <div class="container text-center">
        <h1 class="fw-bold mb-3" style="font-size:46px;">
            <i class="bi bi-images text-primary me-2"></i>Gallery Transport
        </h1>
        <p class="mb-0" style="opacity:.85;">
            Home / <span class="text-primary">Gallery</span>
        </p>
    </div>
</section>

{{-- GALLERY --}}
<section class="py-5 bg-light animate-section">
    <div class="container">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- CARD HEADER (TULISAN DI ATAS GAMBAR) --}}
            <div class="card-header bg-white text-center py-4">
                <h3 class="fw-bold mb-1">Dokumentasi Armada Transport</h3>
                <p class="text-muted mb-0">
                    PT. Budi Jaya Marine
                </p>
            </div>

            <div class="card-body p-4">

                {{-- MAIN IMAGE + NAV --}}
                <div class="gallery-main mb-4">

                    <button class="nav-btn left" onclick="prevImage()">‹</button>

                    <img
                        id="mainImage"
                        src="{{ asset('assets/gallerymobil/IMG_20230218_132102.jpg') }}"
                        class="main-img rounded-3"
                        alt="Gallery Image"
                    >

                    <button class="nav-btn right" onclick="nextImage()">›</button>

                </div>

                {{-- THUMBNAILS --}}
                <div class="thumb-wrapper">

                    <img src="{{ asset('assets/gallerymobil/IMG_20230218_132102.jpg') }}" class="thumb active" onclick="changeImage(0)">
                    <img src="{{ asset('assets/gallerymobil/IMG-20250513-WA0238.jpg') }}" class="thumb" onclick="changeImage(1)">
                    <img src="{{ asset('assets/gallerymobil/IMG_20220917_084328.jpg') }}" class="thumb" onclick="changeImage(2)">
                    <img src="{{ asset('assets/gallerymobil/IMG_20221118_124355.jpg') }}" class="thumb" onclick="changeImage(3)">
                    <img src="{{ asset('assets/gallerymobil/IMG_20221129_065819.jpg') }}" class="thumb" onclick="changeImage(4)">
                    <img src="{{ asset('assets/gallerymobil/IMG_20221211_111343.jpg') }}" class="thumb" onclick="changeImage(5)">
                    <img src="{{ asset('assets/gallerymobil/IMG_20230311_171054.jpg') }}" class="thumb" onclick="changeImage(6)">
                    <img src="{{ asset('assets/gallerymobil/IMG-20250411-WA0254.jpg') }}" class="thumb" onclick="changeImage(7)">
                    <img src="{{ asset('assets/gallerymobil/IMG-20250725-WA0129.jpg') }}" class="thumb" onclick="changeImage(8)">
                    <img src="{{ asset('assets/gallerymobil/IMG-20251006-WA0260.jpg') }}" class="thumb" onclick="changeImage(9)">
                    <img src="{{ asset('assets/gallerymobil/IMG-20251024-WA0100.jpg') }}" class="thumb" onclick="changeImage(10)">
                    <img src="{{ asset('assets/gallerymobil/IMG-20251027-WA0119.jpg') }}" class="thumb" onclick="changeImage(11)">

                </div>

            </div>
        </div>

    </div>
</section>

@endsection

@push('style')
<style>
.gallery-main{
    position:relative;
    text-align:center;
}

.main-img{
    width:100%;
    max-height:520px;
    object-fit:cover;
    transition:opacity .3s ease, transform .3s ease;
}

@media (max-width:768px){
    .main-img{
        max-height:none;
        object-fit:contain;
    }
}

.nav-btn{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    background:rgba(0,0,0,.5);
    color:#fff;
    border:none;
    width:46px;
    height:46px;
    font-size:28px;
    border-radius:50%;
    cursor:pointer;
    z-index:5;
}

.nav-btn.left{ left:10px; }
.nav-btn.right{ right:10px; }

.thumb-wrapper{
    display:flex;
    gap:12px;
    justify-content:center;
    flex-wrap:wrap;
}

.thumb{
    width:120px;
    height:80px;
    object-fit:cover;
    cursor:pointer;
    opacity:.5;
    border-radius:8px;
    border:3px solid transparent;
    transition:.25s;
}

.thumb.active{
    opacity:1;
    border-color:#0d6efd;
}
</style>
@endpush

@push('script')
<script>
const images = [
    "{{ asset('assets/gallerymobil/IMG_20230218_132102.jpg') }}",
    "{{ asset('assets/gallerymobil/IMG-20250513-WA0238.jpg') }}",
    "{{ asset('assets/gallerymobil/IMG_20220917_084328.jpg') }}",
    "{{ asset('assets/gallerymobil/IMG_20221118_124355.jpg') }}",
    "{{ asset('assets/gallerymobil/IMG_20221129_065819.jpg') }}",
    "{{ asset('assets/gallerymobil/IMG_20221211_111343.jpg') }}",
    "{{ asset('assets/gallerymobil/IMG_20230311_171054.jpg') }}",
    "{{ asset('assets/gallerymobil/IMG-20250411-WA0254.jpg') }}",
    "{{ asset('assets/gallerymobil/IMG-20250725-WA0129.jpg') }}",
    "{{ asset('assets/gallerymobil/IMG-20251006-WA0260.jpg') }}",
    "{{ asset('assets/gallerymobil/IMG-20251024-WA0100.jpg') }}",
    "{{ asset('assets/gallerymobil/IMG-20251027-WA0119.jpg') }}"
];

let currentIndex = 0;
const mainImage = document.getElementById('mainImage');
const thumbs = document.querySelectorAll('.thumb');

function updateImage(){
    mainImage.style.opacity = 0;
    setTimeout(() => {
        mainImage.src = images[currentIndex];
        thumbs.forEach(t => t.classList.remove('active'));
        thumbs[currentIndex].classList.add('active');
        mainImage.style.opacity = 1;
    }, 200);
}

function nextImage(){
    currentIndex = (currentIndex + 1) % images.length;
    updateImage();
}

function prevImage(){
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    updateImage();
}

function changeImage(index){
    currentIndex = index;
    updateImage();
}
</script>
@endpush
