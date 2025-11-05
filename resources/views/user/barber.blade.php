<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cukur Men - Pilih Barber</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="/assets/css/style-booking.css">
</head>
<body>

    <header class="booking-header-full">
        <a href="{{ route('user.dashboard') }}" class="booking-header-logo"> 
            <img src="/assets/img/logo.png" alt="Cukur Men Logo">
            <div class="logo-text-container"><span class="cukur-text">CUKUR</span><span class="men-text">MEN</span></div>
        </a>
        <div class="sub-nav-full">
            <a href="{{ route('user.service.selection') }}" class="active"><i class="fas fa-book"></i> Book</a>
            <a href="{{ route('user.products') }}"><i class="fas fa-box-open"></i> Produk</a>
            <a href="{{ route('user.profile') }}"><i class="fas fa-user"></i> Profil</a>
        </div>
    </header>

    <main class="main-booking-section">
        <div class="main-booking-container">
            <h2 class="booking-section-title">BUAT <span>BOOKING</span></h2>
            <p class="selection-prompt">Langkah 2: Silahkan Pilih <span>Barber</span></p>
            
            <div class="barber-grid">

                <div class="barber-card-select" data-barber-id="1" data-barber-name="Zaki">
                    <img class="barber-card-img img-show-torso" src="/assets/img/barber1.jpg" alt="Zaki">
                    <div class="barber-info">
                        <h4>Zaki</h4>
                        <div class="barber-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="selection-indicator">
                        <div class="inner-circle"></div>
                    </div>
                </div>

                <div class="barber-card-select" data-barber-id="2" data-barber-name="Safik">
                    <img class="barber-card-img img-show-torso" src="/assets/img/barber2.jpg" alt="Safik">
                    <div class="barber-info">
                        <h4>Safik</h4>
                        <div class="barber-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                     <div class="selection-indicator">
                        <div class="inner-circle"></div>
                    </div>
                </div>

                </div>

            <button class="btn-next-full" id="btnNext" disabled>Selanjutnya</button>
        </div>
    </main>

    <footer class="new-footer">
        <div class="footer-grid">
            <div class="footer-about">
                    <a href="#" class="footer-logo">
                        <img src="/assets/img/logo.png" alt="Logo CUKURMEN">
                        <div class="logo-text-container"><span class="cukur-text">CUKUR</span><span class="men-text">MEN</span></div>
                    </a>
                    <p>CUKURMEN BARBERSHOP</p>
                    <p>Since 2025</p>
                    
                    <div class="footer-socials">
                        <a href="javascript:void(0);"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/cukurmen.barber?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        <a href="javascript:void(0);"><i class="fab fa-tiktok"></i></a>
                        <a href="javascript:void(0);"><i class="fab fa-linkedin-in"></i></a>
                    </div>
            </div>
            <div class="footer-links">
                <h4>COMPANY</h4>
                <ul>
                   
                    <li><a href="/#about-us">Tentang Kami</a></li>
                    <li><a href="/#layanan">Layanan</a></li>
                    <li><a href="/#barber">Barber</a></li>
                    <li><a href="/#produk">Produk</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>OTHER</h4>
                <ul>
                    <li><a href="#">Trend Rambut</a></li>
                    <li><a href="#">Galeri</a></li>
                    <li><a href="#">Karir</a></li>
                    <li><a href="#">Kontak</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Term of Service</a></li>
                </ul>
            </div>
            <div class="footer-support">
                <h4>SUPPORT</h4>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps?q=Jl.%20Profesor%20DR.%20HR%20Boenyamin%20No.152%20Sumampir%20Wetan%20Purwokerto%20Banyumas&z=15&output=embed" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <p><i class="fas fa-map-marker-alt"></i>Jl. Profesor DR. HR Boenyamin No.152, Sumampir Wetan, Pabuaran, Kec. Purwokerto Utara, Kabupaten Banyumas, Jawa Tengah 53124</p>
                <p><i class="fab fa-whatsapp"></i>085228938097</p>
                                <p><i class="fab fa-instagram"></i> cukurmen.barber</p>

            </div>
        </div>
    <div class="sub-footer">
        Copyright © 2025 All rights reserved | <span>CUKURMEN BARBERSHOP</span>
    </div>
    </footer>

    <script src="/assets/js/script-barber.js" defer></script>

</body>
</html>