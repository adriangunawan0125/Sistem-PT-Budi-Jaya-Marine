@extends('user.layouts.app')

@section('title', 'Gallery Transport')

@section('content')

{{-- HERO --}}
<section class="text-white" style="
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
<section class="py-5 bg-light">
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
