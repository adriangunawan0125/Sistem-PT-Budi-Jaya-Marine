@extends('user.layouts.app')

@section('title', 'Gallery Spareparts')

@section('content')

@push('style')
<style>
/* ==========================
   ANIMASI CONTENT GALLERY SERVICE
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

/* Images & Video */
.animate-section img,
.animate-section iframe,
.animate-section video {
    opacity: 0;
    transform: translateY(20px) scale(0.97);
    transition: all 0.8s ease-out;
}

.animate-section.animate img,
.animate-section.animate iframe,
.animate-section.animate video {
    opacity: 1;
    transform: translateY(0) scale(1);
}

/* FRAME */
.gallery-main{
    position:relative;
    height:520px;
    background:#000;
    border-radius:14px;
    overflow:hidden;

    display:flex;
    align-items:center;
    justify-content:center;
}

/* MEDIA */
.main-media{
    max-width:100%;
    max-height:100%;
    object-fit:contain;
    display:none;
    opacity:0;
    transition:opacity .35s ease;
}

/* NAV */
.nav-btn{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    background:rgba(0,0,0,.55);
    color:#fff;
    border:none;
    width:46px;
    height:46px;
    font-size:28px;
    border-radius:50%;
    cursor:pointer;
    z-index:5;
}
.nav-btn.left{ left:14px; }
.nav-btn.right{ right:14px; }

/* THUMB */
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
    background:#000;
}

.thumb.active{
    opacity:1;
    border-color:#0d6efd;
}

.video-thumb{
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:32px;
}

/* MOBILE */
@media (max-width:768px){
    .gallery-main{
        height:auto;
        padding:12px;
    }
    .main-media{
        max-height:70vh;
    }
    .thumb{
        width:90px;
        height:60px;
    }
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
        <h1 class="fw-bold mb-3" style="font-size:46px;">
            <i class="bi bi-tools text-primary me-2"></i>Gallery Service
        </h1>
        <p class="mb-0" style="opacity:.85;">
            Home / <span class="text-primary">Service</span>
        </p>
    </div>
</section>

{{-- GALLERY --}}
<section class="py-5 bg-light animate-section">
    <div class="container">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="card-header bg-white text-center py-4">
                <h3 class="fw-bold mb-1">Dokumentasi Marine Spareparts</h3>
                <p class="text-muted mb-0">Ketersediaan & Kualitas Terjamin</p>
            </div>


      <div class="card-body p-4">

                {{-- MAIN MEDIA --}}
                <div class="gallery-main mb-4">

                    <button class="nav-btn left" onclick="prevMedia()">‹</button>

                    <img id="mainImage" class="main-media">

                    <video id="mainVideo"
                           class="main-media"
                           controls
                           preload="metadata"
                           playsinline>
                    </video>

                    <button class="nav-btn right" onclick="nextMedia()">›</button>

                </div>

                {{-- THUMBNAILS --}}
                <div class="thumb-wrapper">

                    {{-- VIDEO --}}
                    <div class="thumb video-thumb active" onclick="changeMedia(0)">
                        <i class="bi bi-play-circle-fill"></i>
                    </div>

                    <img src="{{ asset('assets/galleryspareparts/sparepart1.jpg') }}" class="thumb" onclick="changeImage(1)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart2.jpg') }}" class="thumb" onclick="changeImage(2)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart3.jpg') }}" class="thumb" onclick="changeImage(3)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart4.jpg') }}" class="thumb" onclick="changeImage(4)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart5.jpg') }}" class="thumb" onclick="changeImage(5)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart6.jpg') }}" class="thumb" onclick="changeImage(6)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart7.jpg') }}" class="thumb" onclick="changeImage(7)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart8.jpg') }}" class="thumb" onclick="changeImage(8)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart9.jpg') }}" class="thumb" onclick="changeImage(9)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart10.jpg') }}" class="thumb" onclick="changeImage(10)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart11.jpeg') }}" class="thumb" onclick="changeImage(11)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart12.jpeg') }}" class="thumb" onclick="changeImage(12)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart13.jpeg') }}" class="thumb" onclick="changeImage(13)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart14.jpeg') }}" class="thumb" onclick="changeImage(14)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart15.jpeg') }}" class="thumb" onclick="changeImage(15)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart16.jpeg') }}" class="thumb" onclick="changeImage(16)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart17.jpeg') }}" class="thumb" onclick="changeImage(17)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart18.jpeg') }}" class="thumb" onclick="changeImage(18)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart19.jpeg') }}" class="thumb" onclick="changeImage(19)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart20.jpeg') }}" class="thumb" onclick="changeImage(20)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart21.jpeg') }}" class="thumb" onclick="changeImage(21)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart22.jpeg') }}" class="thumb" onclick="changeImage(22)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart23.jpeg') }}" class="thumb" onclick="changeImage(23)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart24.jpeg') }}" class="thumb" onclick="changeImage(24)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart25.jpeg') }}" class="thumb" onclick="changeImage(25)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart26.jpeg') }}" class="thumb" onclick="changeImage(26)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart27.jpeg') }}" class="thumb" onclick="changeImage(27)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart28.jpeg') }}" class="thumb" onclick="changeImage(28)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart29.jpeg') }}" class="thumb" onclick="changeImage(29)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart30.jpeg') }}" class="thumb" onclick="changeImage(30)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart31.jpeg') }}" class="thumb" onclick="changeImage(31)">
                    <img src="{{ asset('assets/galleryspareparts/sparepart32.jpeg') }}" class="thumb" onclick="changeImage(32)">
                </div>

            </div>
        </div>

    </div>
</section>

@endsection

@push('style')
@endpush

@push('script')
<script>
const media = [
    { type: 'video', src: "{{ asset('assets/galleryspareparts/vid1.mp4') }}" },

    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart1.jpg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart2.jpg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart3.jpg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart4.jpg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart5.jpg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart6.jpg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart7.jpg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart8.jpg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart9.jpg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart10.jpg') }}" },

    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart11.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart12.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart13.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart14.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart15.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart16.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart17.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart18.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart19.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart20.jpeg') }}" },

    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart21.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart22.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart23.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart24.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart25.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart26.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart27.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart28.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart29.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart30.jpeg') }}" },

    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart31.jpeg') }}" },
    { type: 'image', src: "{{ asset('assets/galleryspareparts/sparepart32.jpeg') }}" }
];


let currentIndex = 0;

const mainImage = document.getElementById('mainImage');
const mainVideo = document.getElementById('mainVideo');
const thumbs = document.querySelectorAll('.thumb');

function showMedia(el){
    el.style.display = 'block';
    requestAnimationFrame(() => el.style.opacity = 1);
}

function hideMedia(el){
    el.style.opacity = 0;
    setTimeout(() => el.style.display = 'none', 300);
}

function updateMedia(){
    hideMedia(mainImage);
    hideMedia(mainVideo);
    mainVideo.pause();

    setTimeout(() => {
        if(media[currentIndex].type === 'image'){
            mainImage.src = media[currentIndex].src;
            showMedia(mainImage);
        }else{
            mainVideo.src = media[currentIndex].src;
            showMedia(mainVideo);
        }

        thumbs.forEach(t => t.classList.remove('active'));
        if(thumbs[currentIndex]) thumbs[currentIndex].classList.add('active');
    }, 300);
}

function nextMedia(){
    currentIndex = (currentIndex + 1) % media.length;
    updateMedia();
}

function prevMedia(){
    currentIndex = (currentIndex - 1 + media.length) % media.length;
    updateMedia();
}

function changeMedia(index){
    currentIndex = index;
    updateMedia();
}

updateMedia();
</script>
@endpush

        
                  