<style>
    /* FOOTER LIGHT */
.footer-light {
    background: #fff;
    color: #555;
    padding: 70px 0 0;
    border-top: 1px solid #eee;
}

.footer-light h4,
.footer-light h5 {
    color: #050a30;
    font-weight: 600;
}

.footer-desc {
    font-size: 14px;
    line-height: 1.7;
    margin-bottom: 15px;
}

/* CONTACT */
.footer-contact li {
    font-size: 14px;
    margin-bottom: 8px;
}

.footer-contact i {
    color: #4f6ef7;
    margin-right: 10px;
}

/* LINKS */
.footer-links li {
    margin-bottom: 10px;
}

.footer-links a {
    color: #555;
    text-decoration: none;
    transition: color .3s;
}

.footer-links a:hover {
    color: #4f6ef7;
}

/* SOCIAL */
.footer-social a {
    width: 38px;
    height: 38px;
    background: #f4f6ff;
    color: #4f6ef7;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-right: 10px;
    transition: all .3s;
}

.footer-social a:hover {
    background: #4f6ef7;
    color: #fff;
    transform: translateY(-3px);
}

/* BOTTOM */
.footer-bottom {
    margin-top: 50px;
    background: #f9f9f9;
    padding: 15px 0;
    font-size: 13px;
    color: #777;
}
</style>

<!-- FOOTER -->
<footer class="footer footer-light">
    <div class="container">
        <div class="row gy-4">

            <!-- COMPANY INFO -->
            <div class="col-md-4">
                <div class="footer-brand d-flex align-items-center gap-3 mb-3">
                    <img src="assets/logo.png" alt="BJM Logo" height="45">
                    <h4 class="mb-0">Budi Jaya Marine</h4>
                </div>

                <p class="footer-desc">
                    Budi Jaya Marine merupakan perusahaan pengadaan
                    marine spare parts, marine services, dan transportasi darat
                    yang profesional dan terpercaya.
                </p>

                <ul class="footer-contact list-unstyled">
                    <!-- JAM KERJA (ATAS) -->
                    <li>
                        <i class="fa fa-clock"></i>
                        Jam Kerja: 08.00 – 17.00
                    </li>

                    <!-- TELEPON (BAWAH) -->
                    <li>
                        <i class="fa fa-phone"></i>
                        0877-7023-9693
                    </li>
                </ul>

                <div class="footer-social mt-3">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>

                    <!-- EMAIL (GANTI LINKEDIN) -->
                    <a href="mailto:budijayamarine@gmail.com">
                        <i class="fa fa-envelope"></i>
                    </a>
                </div>
            </div>

            <!-- NAVIGATION -->
            <div class="col-md-4">
                <h5 class="footer-title">Navigasi</h5>
                <ul class="footer-links list-unstyled">
                    <li><a href="/">Home</a></li>
                    <li><a href="/tentang-kami">Tentang Kami</a></li>
                    <li><a href="#">Gallery</a></li>
                    <li><a href="/hubungi-kami">Hubungi Kami</a></li>
                    <li><a href="#">Jadi Mitra Kami</a></li>
                </ul>
            </div>

            <!-- SERVICES -->
            <div class="col-md-4">
                <h5 class="footer-title">Layanan</h5>
                <ul class="footer-links list-unstyled">
                    <li><i class="fa fa-car me-2"></i> Transportation</li>
                    <li><i class="fa fa-ship me-2"></i> Marine Services</li>
                    <li><i class="fa fa-screwdriver-wrench me-2"></i> Marine Spareparts</li>
                </ul>
            </div>

        </div>
    </div>

<!-- FOOTER BOTTOM -->
<div class="footer-bottom py-3 border-top">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-md-6 text-center text-md-start">
                © 2025 Budi Jaya Marine. All Rights Reserved.
            </div>

            <div class="col-md-6 mt-3 text-center text-md-end">
                <a href="/login" class="btn btn-sm btn-primary rounded-pill px-3">
                    Login Admin
                </a>
            </div>

        </div>
    </div>
</div>

</footer>
