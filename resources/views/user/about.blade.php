@extends('user.layouts.app')

@section('title', 'Tentang Kami')

@section('content')
<!-- HERO / BANNER -->
<section style="
    background: linear-gradient(rgba(5,10,48,.75), rgba(5,10,48,.75)),
                url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee') center/cover;
    padding:180px 0 90px;
    color:#fff;
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
<section style="padding:80px 0;background:#ffffff;">
    <div class="container">

        <div class="row align-items-center">

            <!-- IMAGE -->
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="assets\bgabout.jpg"
                     style="
                        width:100%;
                        border-radius:26px;
                        box-shadow:0 25px 60px rgba(0,0,0,.25);
                     ">
            </div>

            <!-- TEXT -->
            <div class="col-md-6">
                <h2 style="
                    font-weight:700;
                    color:#050a30;
                    margin-bottom:20px;
                ">
                    PT. Budi Jaya Marine
                </h2>

                <p style="
                    font-size:15px;
                    line-height:1.9;
                    color:#555;
                    margin-bottom:18px;
                ">
                    PT Budi Jaya Marine merupakan perusahaan yang bergerak di bidang jasa kontraktor, general supplier, dan transportasi. Kami melayani berbagai sektor usaha, mulai dari usaha menengah hingga perusahaan skala besar, baik perusahaan swasta maupun Badan Usaha Milik Negara (BUMN).

                </p>

                <p style="
                    font-size:15px;
                    line-height:1.9;
                    color:#555;
                ">
                    PT Budi Jaya Marine didirikan pada tahun 2022. Dengan pengalaman hampir 4 tahun, kami memiliki komitmen tinggi dalam memberikan pelayanan terbaik kepada setiap klien. Saat ini, fokus utama perusahaan kami meliputi penyediaan tenaga kerja (manpower) yang berkualitas, pengadaan barang dengan harga yang kompetitif, serta penyediaan teknisi ahli di bidang maritim yang profesional dan berpengalaman.
                </p>
                <p style="
                    font-size:15px;
                    line-height:1.9;
                    color:#555;
                ">
                    Tujuan utama perusahaan kami adalah memberikan layanan terbaik dengan tetap menjaga standar kualitas yang tinggi. Dalam mencapai tujuan tersebut, PT Budi Jaya Marine didukung oleh sumber daya manusia yang profesional, kompeten, dan berintegritas. Hal ini kami lakukan untuk memastikan kepuasan pelanggan yang telah mempercayakan kami sebagai mitra dalam mendukung kegiatan operasional usaha mereka.
Saat ini, PT Budi Jaya Marine menyediakan layanan pada beberapa bidang usaha, yang masing-masing dikelola oleh tim ahli dan profesional, meliputi:
                </p>
                 <p style="
                    font-size:15px;
                    line-height:1.9;
                    color:#555;
                ">
                   1. Marine Spare Parts Sebagai penyedia layanan pengadaan barang dan suku cadang perkapalan dalam skala besar dengan kualitas terjamin.
                </p>
                 <p style="
                    font-size:15px;
                    line-height:1.9;
                    color:#555;
                ">
                    
2. Marine Services Menyediakan jasa inspeksi, troubleshooting, konsultasi, perbaikan, dan perawatan umum (general maintenance), termasuk layanan engine services, auxiliary engine services, serta propulsion system services.
                </p>
                 <p style="
                    font-size:15px;
                    line-height:1.9;
                    color:#555;
                ">
                  3. Transportasi Menyediakan jasa layanan transportasi berbasis kontrak perusahaan untuk kebutuhan jangka menengah (6 bulanan) maupun jangka panjang (tahunan).

                </p>
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
