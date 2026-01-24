@extends('user.layouts.app')

@section('title', 'Tentang Kami')

@section('content')

<style>
/* ==========================
   FULL SMOOTH ANIMASI TENTANG KAMI & ALASAN MEMILIH KAMI
   - HERO/BANNER TIDAK ANIMASI
=========================== */

/* Section content (skip hero-carousel) */
section:not(.hero-carousel) {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.9s ease-out;
}

/* Animate muncul */
section.animate {
    opacity: 1;
    transform: translateY(0);
}

/* Heading & paragraf */
section h1,
section h2,
section h5,
section p {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.9s ease-out;
}

section.animate h1,
section.animate h2,
section.animate h5,
section.animate p {
    opacity: 1;
    transform: translateY(0);
}

/* Stagger paragraf */
section.animate p:nth-of-type(1) { transition-delay: 0.2s; }
section.animate p:nth-of-type(2) { transition-delay: 0.35s; }
section.animate p:nth-of-type(3) { transition-delay: 0.5s; }
section.animate p:nth-of-type(4) { transition-delay: 0.65s; }
section.animate p:nth-of-type(5) { transition-delay: 0.8s; }

/* Image fade + zoom */
section img {
    opacity: 0;
    transform: translateY(20px) scale(0.97);
    transition: all 0.9s ease-out;
}
section.animate img {
    opacity: 1;
    transform: translateY(0) scale(1);
}

/* ==========================
   ALASAN MEMILIH KAMI - Card
=========================== */

/* Card awal */
.row.g-4 > .col-md-4 div {
    opacity: 0;
    transform: translateY(20px) scale(0.97);
    transition: all 0.5s ease-out; /* hover masuk & keluar smooth */
}

/* Card muncul dari bawah */
.row.g-4 > .col-md-4.animate div {
    opacity: 1;
    transform: translateY(0) scale(1);
}

/* Hover card smooth */
.row.g-4 > .col-md-4 div:hover {
    transform: scale(1.03);
}

/* Icon di card */
.reason-icon i {
    transition: transform 0.5s ease-out, color 0.5s ease-out;
}

.reason-item:hover .reason-icon i {
    transform: rotate(10deg) scale(1.15);
    color:#15287f;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // Animasi section content (Tentang Kami)
    const sections = document.querySelectorAll('section:not(.hero-carousel)');
    const observerSection = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting){
                entry.target.classList.add('animate');
            }
        });
    }, { threshold: 0.2 });
    sections.forEach(section => observerSection.observe(section));

    // Animasi cards Alasan Memilih Kami dengan stagger smooth
    const cards = document.querySelectorAll('.row.g-4 > .col-md-4');
    const observerCards = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if(entry.isIntersecting){
                setTimeout(() => {
                    entry.target.classList.add('animate');
                }, index * 300); // delay per card lebih smooth
            }
        });
    }, { threshold: 0.2 });
    cards.forEach(card => observerCards.observe(card));

});
</script>

<!-- HERO / BANNER -->
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
    <div class="container text-center" style="margin-top:30px;">
        <h1 style="
            font-weight:700;
            font-size:42px;
            margin-bottom:10px;
        ">
            Tentang Kami
        </h1>

        <p style="
            font-size:14px;
            opacity:.9;
            margin:0;
        ">
            Home / <span style="color:#4f6ef7;">Tentang Kami</span>
        </p>

    </div>
</section>


<!-- ABOUT CONTENT -->
<section class="py-5 bg-white">
    <div class="container py-5">

        <!-- ROW ATAS -->
        <div class="row align-items-start g-5">

            <!-- IMAGE -->
            <div class="col-lg-5 col-md-6">
                <div
                    style="
                        border-radius:30px;
                        overflow:hidden;
                        box-shadow:0 25px 60px rgba(0,0,0,.25);
                    "
                >
                    <img
                        src="/assets/A-List-of-Inspections-And-Surveys-Deck-Officers-On-Ships-Should-Be-Aware-Of.jpg"
                        alt="PT Budi Jaya Marine"
                        class="img-fluid"
                    >
                </div>
            </div>

            <!-- TEXT (KANAN GAMBAR) -->
            <div class="col-lg-7 col-md-6">

                <h2 class="fw-bold mb-4" style="color:#050a30;">
                    PT. Budi Jaya Marine
                </h2>

                <p style="
                    font-size:16.5px;
                    line-height:1.9;
                    text-align:justify;
                    color:#555;
                    margin-bottom:16px;
                ">
                    PT Budi Jaya Marine merupakan perusahaan yang bergerak di bidang jasa kontraktor,
                    general supplier, dan transportasi. Kami melayani berbagai sektor usaha, mulai dari
                    usaha menengah hingga perusahaan skala besar, baik perusahaan swasta maupun
                    Badan Usaha Milik Negara (BUMN).
                </p>

                <p style="
                    font-size:16.5px;
                    line-height:1.9;
                    text-align:justify;
                    color:#555;
                ">
                    PT Budi Jaya Marine didirikan pada tahun 2022. Dengan pengalaman hampir 4 tahun,
                    kami memiliki komitmen tinggi dalam memberikan pelayanan terbaik kepada setiap klien.
                </p>

            </div>
        </div>

        <!-- ROW BAWAH (TEXT FULL WIDTH) -->
        <div class="row mt-4">
            <div class="col-12">

                <p style="
                    font-size:16.5px;
                    line-height:1.9;
                    text-align:justify;
                    color:#555;
                    margin-bottom:16px;
                ">
                    Fokus utama perusahaan kami meliputi penyediaan tenaga kerja (manpower) berkualitas,
                    pengadaan barang dengan harga kompetitif, serta penyediaan teknisi maritim yang
                    profesional dan berpengalaman.
                </p>

                <p style="
                    font-size:16.5px;
                    line-height:1.9;
                    text-align:justify;
                    color:#555;
                    margin-bottom:22px;
                ">
                    Tujuan utama perusahaan kami adalah memberikan layanan terbaik dengan tetap menjaga
                    standar kualitas yang tinggi. Dalam mencapai tujuan tersebut, PT Budi Jaya Marine
                    didukung oleh sumber daya manusia yang profesional, kompeten, dan berintegritas guna
                    memastikan kepuasan pelanggan.
                </p>

                <p class="fw-semibold mb-3" style="font-size:17px;color:#050a30;">
                    Bidang usaha yang kami layani:
                </p>

                <ol style="
                    font-size:16.5px;
                    line-height:1.9;
                    color:#555;
                    padding-left:18px;
                ">
                    <li class="mb-2">
                        <strong>Marine Spare Parts</strong> – Pengadaan barang dan suku cadang perkapalan
                        dalam skala besar dengan kualitas terjamin.
                    </li>
                    <li class="mb-2">
                        <strong>Marine Services</strong> – Jasa inspeksi, troubleshooting, konsultasi,
                        perbaikan, dan perawatan umum, termasuk engine services, auxiliary engine services,
                        serta propulsion system services.
                    </li>
                    <li>
                        <strong>Transportasi</strong> – Layanan transportasi berbasis kontrak perusahaan
                        untuk kebutuhan jangka menengah (6 bulanan) maupun jangka panjang (tahunan).
                    </li>
                </ol>

            </div>
        </div>

    </div>
</section>

<!-- ALASAN MEMILIH KAMI -->
<section style="padding:80px 0;background:#4f6ef7">
    <div class="container">

        <!-- JUDUL -->
        <div class="text-center mb-5">
            <h2 class="mb-3" style="
                font-weight:700;
                color:white;
                margin-bottom:10px;
            ">
                Alasan Memilih Kami
            </h2>
            <p style="
                font-size:15px;
                color:white;
                max-width:650px;
                margin:auto;
            ">
                Kami berkomitmen menjadi partner terpercaya dengan layanan terbaik
                untuk mendukung kebutuhan bisnis Anda.
            </p>
        </div>

        <!-- LIST -->
        <div class="row g-4">

            <!-- TERJANGKAU -->
            <div class="col-md-4">
                <div style="
                    background:#fff;
                    padding:35px 25px;
                    border-radius:24px;
                    text-align:center;
                    box-shadow:0 20px 45px rgba(0,0,0,.08);
                    height:100%;
                ">
                    <div style="
                        width:80px;height:80px;
                        background:#f4f6ff;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        margin:0 auto 20px;
                    ">
                        <i class="fa fa-tags" style="font-size:36px;color:#4f6ef7;"></i>
                    </div>
                    <h5 style="font-weight:600;color:#050a30;">Harga Terjangkau</h5>
                    <p style="font-size:14px;color:#555;line-height:1.8;">
                        Menawarkan harga yang kompetitif dan sesuai
                        dengan kualitas layanan yang diberikan.
                    </p>
                </div>
            </div>

            <!-- LEGALITAS -->
            <div class="col-md-4">
                <div style="
                    background:#fff;
                    padding:35px 25px;
                    border-radius:24px;
                    text-align:center;
                    box-shadow:0 20px 45px rgba(0,0,0,.08);
                    height:100%;
                ">
                    <div style="
                        width:80px;height:80px;
                        background:#f4f6ff;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        margin:0 auto 20px;
                    ">
                        <i class="fa fa-scale-balanced" style="font-size:36px;color:#4f6ef7;"></i>
                    </div>
                    <h5 style="font-weight:600;color:#050a30;">Legalitas Jelas</h5>
                    <p style="font-size:14px;color:#555;line-height:1.8;">
                        Perusahaan memiliki legalitas resmi dan
                        beroperasi sesuai dengan peraturan yang berlaku.
                    </p>
                </div>
            </div>

            <!-- RESPONSIF -->
            <div class="col-md-4">
                <div style="
                    background:#fff;
                    padding:35px 25px;
                    border-radius:24px;
                    text-align:center;
                    box-shadow:0 20px 45px rgba(0,0,0,.08);
                    height:100%;
                ">
                    <div style="
                        width:80px;height:80px;
                        background:#f4f6ff;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        margin:0 auto 20px;
                    ">
                        <i class="fa fa-headset" style="font-size:36px;color:#4f6ef7;"></i>
                    </div>
                    <h5 style="font-weight:600;color:#050a30;">Responsif</h5>
                    <p style="font-size:14px;color:#555;line-height:1.8;">
                        Tim kami siap merespons kebutuhan klien
                        dengan cepat dan solusi yang tepat.
                    </p>
                </div>
            </div>

            <!-- PROFESIONAL -->
            <div class="col-md-4">
                <div style="
                    background:#fff;
                    padding:35px 25px;
                    border-radius:24px;
                    text-align:center;
                    box-shadow:0 20px 45px rgba(0,0,0,.08);
                    height:100%;
                ">
                    <div style="
                        width:80px;height:80px;
                        background:#f4f6ff;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        margin:0 auto 20px;
                    ">
                        <i class="fa fa-user-tie" style="font-size:36px;color:#4f6ef7;"></i>
                    </div>
                    <h5 style="font-weight:600;color:#050a30;">Profesional</h5>
                    <p style="font-size:14px;color:#555;line-height:1.8;">
                        Dikerjakan oleh tenaga ahli yang
                        berpengalaman dan profesional di bidangnya.
                    </p>
                </div>
            </div>

            <!-- VISI -->
            <div class="col-md-4">
                <div style="
                    background:#fff;
                    padding:35px 25px;
                    border-radius:24px;
                    text-align:center;
                    box-shadow:0 20px 45px rgba(0,0,0,.08);
                    height:100%;
                ">
                    <div style="
                        width:80px;height:80px;
                        background:#f4f6ff;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        margin:0 auto 20px;
                    ">
                        <i class="fa fa-eye" style="font-size:36px;color:#4f6ef7;"></i>
                    </div>
                    <h5 style="font-weight:600;color:#050a30;">Visi Jelas</h5>
                    <p style="font-size:14px;color:#555;line-height:1.8;">
                        Memiliki visi menjadi perusahaan marine
                        dan transportasi yang unggul dan terpercaya.
                    </p>
                </div>
            </div>

            <!-- MISI -->
            <div class="col-md-4">
                <div style="
                    background:#fff;
                    padding:35px 25px;
                    border-radius:24px;
                    text-align:center;
                    box-shadow:0 20px 45px rgba(0,0,0,.08);
                    height:100%;
                ">
                    <div style="
                        width:80px;height:80px;
                        background:#f4f6ff;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        margin:0 auto 20px;
                    ">
                        <i class="fa fa-bullseye" style="font-size:36px;color:#4f6ef7;"></i>
                    </div>
                    <h5 style="font-weight:600;color:#050a30;">Misi Terarah</h5>
                    <p style="font-size:14px;color:#555;line-height:1.8;">
                        Fokus memberikan solusi terbaik dan
                        membangun kerja sama jangka panjang.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>


@endsection
