<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cukur Men - Katalog Produk</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" href="/assets/img/logo.png" sizes="32x32">
    
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
            <a href="{{ route('user.service.selection') }}"><i class="fas fa-book"></i> Book</a>
            <a href="{{ route('user.products') }}" class="active" ><i class="fas fa-box-open"></i> Produk</a>
            <a href="{{ route('user.profile') }}"><i class="fas fa-user"></i> Profil</a>
        </div>
    </header>
    <main class="main-booking-section">
        <div class="main-booking-container">
            <h2 class="booking-section-title">Katalog <span>Produk</span></h2>
            <p class="selection-prompt">Temukan produk perawatan rambut premium kami. Pembelian hanya dapat dilakukan langsung di barbershop.</p>
            
            <div class="product-grid">
                @if($products->count() > 0)
                    @foreach($products as $product)
                    <div class="product-card">
                        <img class="product-card-img" src="{{ $product->image_path ?? '/assets/img/product-default.jpg' }}" alt="{{ $product->name }}" onerror="this.onerror=null; this.src='/assets/img/logo.png';">
                        <h3>{{ $product->name }}</h3>
                        <span class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <span class="stock-status">{{ $product->status == 'active' ? 'Ready Stock' : ucfirst($product->status) }}</span>
                        <p class="purchase-info"><i class="fas fa-store"></i> Beli di tempat</p>
                    </div>
                    @endforeach
                @else
                    <div class="no-products">
                        <p>Tidak ada produk yang tersedia saat ini.</p>
                    </div>
                @endif
                </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Produk page doesn't typically have next button for selection like layanan/barber
            // but adding this in case there's similar functionality required
            console.log("Product page loaded");
        });
    </script>

</body>
</html>
