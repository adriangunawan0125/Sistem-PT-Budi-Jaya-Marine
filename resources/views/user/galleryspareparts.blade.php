@extends('user.layouts.app')

@section('title', 'Gallery Spareparts')

@section('content')
@push('style')
<style>
/* ==========================
   ANIMASI CONTENT GALLERY SPAREPARTS
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
.animate-section img {
    opacity: 0;
    transform: translateY(20px) scale(0.97);
    transition: all 0.8s ease-out;
}

.animate-section.animate img {
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
    url('{{ asset('assets/bg-spareparts.jpg') }}') center/cover no-repeat;
    padding: 160px 0 90px;
">
    <div class="container text-center">
        <h1 class="fw-bold mb-3" style="font-size:46px;">
            <i class="bi bi-gear-wide-connected text-primary me-2"></i>Gallery Spareparts
        </h1>
        <p class="mb-0" style="opacity:.85;">
            Home / <span class="text-primary">Spareparts</span>
        </p>
    </div>
</section>

{{-- GALLERY --}}
<section class="py-5 bg-light animate-section">
    <div class="container">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- CARD HEADER --}}
            <div class="card-header bg-white text-center py-4">
                <h3 class="fw-bold mb-1">Dokumentasi Spareparts & Komponen</h3>
                <p class="text-muted mb-0">
                    Ketersediaan & Kualitas Terjamin
                </p>
            </div>

            <div class="card-body p-4">

                {{-- MAIN IMAGE --}}
                <div class="gallery-main mb-4">

                    <button class="nav-btn left" onclick="prevImage()">‹</button>

                    <img
                        id="mainImage"
                        src="{{ asset('assets/galleryspareparts/sparepart1.jpg') }}"
                        class="main-img rounded-3"
                        alt="Gallery Spareparts"
                    >

                    <button class="nav-btn right" onclick="nextImage()">›</button>

                </div>

                {{-- THUMBNAILS --}}
                <div class="thumb-wrapper">

                    <img src="{{ asset('assets/galleryspareparts/sparepart1.jpg') }}" class="thumb active" onclick="changeImage(0)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart2.jpg') }}" class="thumb" onclick="changeImage(1)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart3.jpg') }}" class="thumb" onclick="changeImage(2)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart4.jpg') }}" class="thumb" onclick="changeImage(3)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart5.jpg') }}" class="thumb" onclick="changeImage(4)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart6.jpg') }}" class="thumb" onclick="changeImage(5)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart7.jpg') }}" class="thumb" onclick="changeImage(6)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart8.jpg') }}" class="thumb" onclick="changeImage(7)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart9.jpg') }}" class="thumb" onclick="changeImage(8)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart10.jpg') }}" class="thumb" onclick="changeImage(9)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart11.jpeg') }}" class="thumb" onclick="changeImage(10)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart12.jpg') }}" class="thumb" onclick="changeImage(11)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart13.jpeg') }}" class="thumb" onclick="changeImage(12)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart14.jpeg') }}" class="thumb" onclick="changeImage(13)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart15.jpeg') }}" class="thumb" onclick="changeImage(14)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart16.jpeg') }}" class="thumb" onclick="changeImage(15)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart17.jpeg') }}" class="thumb" onclick="changeImage(16)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart18.jpeg') }}" class="thumb" onclick="changeImage(17)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart19.jpeg') }}" class="thumb" onclick="changeImage(18)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart20.jpeg') }}" class="thumb" onclick="changeImage(19)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart21.jpeg') }}" class="thumb" onclick="changeImage(20)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart22.jpeg') }}" class="thumb" onclick="changeImage(21)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart23.jpeg') }}" class="thumb" onclick="changeImage(22)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart24.jpeg') }}" class="thumb" onclick="changeImage(23)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart25.jpeg') }}" class="thumb" onclick="changeImage(24)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart26.jpeg') }}" class="thumb" onclick="changeImage(25)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart27.jpeg') }}" class="thumb" onclick="changeImage(26)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart28.jpeg') }}" class="thumb" onclick="changeImage(27)">
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
    transition:opacity .3s ease;
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
    "{{ asset('assets/galleryspareparts/sparepart1.jpg') }}",
    "{{ asset('assets/galleryspareparts/sparepart2.jpg') }}",
    "{{ asset('assets/galleryspareparts/sparepart3.jpg') }}",
    "{{ asset('assets/galleryspareparts/sparepart4.jpg') }}",
    "{{ asset('assets/galleryspareparts/sparepart5.jpg') }}",
    "{{ asset('assets/galleryspareparts/sparepart6.jpg') }}",
    "{{ asset('assets/galleryspareparts/sparepart7.jpg') }}",
    "{{ asset('assets/galleryspareparts/sparepart8.jpg') }}",
    "{{ asset('assets/galleryspareparts/sparepart9.jpg') }}",
    "{{ asset('assets/galleryspareparts/sparepart10.jpg') }}",
    "{{ asset('assets/galleryspareparts/sparepart11.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart12.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart13.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart14.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart15.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart16.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart17.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart18.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart19.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart20.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart21.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart22.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart23.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart24.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart25.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart26.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart27.jpeg') }}",
    "{{ asset('assets/galleryspareparts/sparepart28.jpeg') }}"
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
