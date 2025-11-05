<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cukur Men - Profil Saya</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="/assets/css/style-booking.css">
</head>

<body>

    <header class="booking-header-full">
        <a href="{{ route('user.dashboard') }}" class="booking-header-logo">
            <img src="/assets/img/logo.png" alt="Cukur Men Logo">
            <div class="logo-text-container"><span class="cukur-text">CUKUR</span><span class="men-text">MEN</span>
            </div>
        </a>

        <div class="sub-nav-full">
            <a href="{{ route('user.service.selection') }}"><i class="fas fa-book"></i> Book</a>
            <a href="{{ route('user.products') }}"><i class="fas fa-box-open"></i> Produk</a>
            <a href="{{ route('user.profile') }}" class="active"><i class="fas fa-user"></i> Profil</a>
        </div>
    </header>
    <main class="main-booking-section">
        <div class="main-booking-container">
            <h2 class="booking-section-title">Profil <span>Saya</span></h2>
            <p class="selection-prompt">Kelola profil Anda, lihat riwayat booking, dan cek progress loyalitas.</p>

            <div class="profile-card-main">
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <div class="user-details">
                        <span class="user-name" id="user-name">{{ $user->name ?? 'Memuat...' }}</span>
                        <span class="user-contact" id="user-email">{{ $user->email ?? 'Memuat...' }}</span>
                        <span class="user-contact" id="user-phone">{{ $user->phone ?? 'Memuat...' }}</span>
                        <span class="user-status">Member Online</span>
                    </div>
                </div>

                <div class="profile-summary-grid">
                    <div class="summary-card">
                        <div class="value" id="total-booking">{{ $totalBookings ?? '...' }}</div>
                        <div class="label">Total Booking</div>
                    </div>
                    <div class="summary-card">
                        <div class="value" id="booking-selesai">{{ $completedBookings ?? '...' }}</div>
                        <div class="label">Selesai</div>
                    </div>
                    <div class="summary-card">
                        <div class="value" id="kupon-aktif">{{ $activeCoupons ?? '...' }}</div>
                        <div class="label">Kupon Aktif</div>
                    </div>
                    <div class="summary-card">
                        <div class="value" id="total-belanja">Rp {{ number_format($totalSpending ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="label">Total Belanja</div>
                    </div>
                </div>

                <div class="loyalty-program">
                    <h4><i class="fas fa-gift"></i>Program Loyalitas</h4>
                    <div class="progress-info">
                        <span>Progress menuju kupon berikutnya</span>
                        <span id="loyalty-progress">{{ $loyaltyProgress ?? '...' }}/5</span>
                    </div>
                    <div class="progress-bar">
                        @php
                            $progressPercent = isset($loyaltyProgress) ? ($loyaltyProgress / 5) * 100 : 0;
                        @endphp
                        <div class="progress-bar-inner" id="loyalty-progress-bar"
                            style="width: {{ $progressPercent }}%;"></div>
                    </div>
                    <div class="loyalty-tips">
                        <i class="fas fa-lightbulb"></i>
                        <p><strong>Tips:</strong> Booking 5 kali untuk mendapatkan 1 layanan gratis!</p>
                    </div>
                </div>

                <a href="/" class="btn-logout" id="logoutButton">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </div>

            <div class="booking-history">
                <div class="history-tabs">
                    <button class="history-tab-btn active" data-tab="mendatang">Booking Mendatang</button>
                    <button class="history-tab-btn" data-tab="riwayat">Riwayat</button>
                </div>
                <div class="history-content">
                    <div id="mendatang" class="tab-pane active">
                        <div class="booking-list" id="mendatangList">
                            <!-- Booking mendatang akan ditampilkan di sini -->
                        </div>
                    </div>
                    <div id="riwayat" class="tab-pane">
                        <div class="booking-list" id="riwayatList">
                            <!-- Riwayat booking akan ditampilkan di sini -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="new-footer">
        <div class="footer-grid">
            <div class="footer-about">
                <a href="#" class="footer-logo">
                    <img src="/assets/img/logo.png" alt="Logo CUKURMEN">
                    <div class="logo-text-container"><span class="cukur-text">CUKUR</span><span
                            class="men-text">MEN</span></div>
                </a>
                <p>CUKURMEN BARBERSHOP</p>
                <p>Since 2025</p>
                <div class="footer-socials">
                    <a href="javascript:void(0);"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/cukurmen.barber?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw=="
                        target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
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
                    <li><a href="javascript:void(0);">Trend Rambut</a></li>
                    <li><a href="javascript:void(0);">Galeri</a></li>
                    <li><a href="javascript:void(0);">Karir</a></li>
                    <li><a href="javascript:void(0);">Kontak</a></li>
                    <li><a href="javascript:void(0);">Privacy Policy</a></li>
                    <li><a href="javascript:void(0);">Term of Service</a></li>
                </ul>
            </div>
            <div class="footer-support">
                <h4>SUPPORT</h4>
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps?q=Jl.%20Profesor%20DR.%20HR%20Boenyamin%20No.152%20Sumampir%20Wetan%20Purwokerto%20Banyumas&z=15&output=embed"
                        allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <p><i class="fas fa-map-marker-alt"></i>Jl. Profesor DR. HR Boenyamin No.152, Sumampir Wetan, Pabuaran,
                    Kec. Purwokerto Utara, Kabupaten Banyumas, Jawa Tengah 53124</p>
                <p><i class="fab fa-whatsapp"></i>085228938097</p>
                <p><i class="fab fa-instagram"></i> cukurmen.barber</p>

            </div>
        </div>
        <div class="sub-footer">
            Copyright © 2025 All rights reserved | <span>CUKURMEN BARBERSHOP</span>
        </div>
    </footer>

    <script src="/assets/js/script-profil.js" defer></script>

    <style>
        /* ====== CONTAINER & LAYOUT ====== */
        .booking-history {
            max-width: 920px;
            /* batasi lebar biar rapi */
            padding: 16px 12px;
            /* ruang napas */
            margin: 0;
            /* tetap rata kiri (no auto-center) */
        }

        .history-tabs {
            display: flex;
            gap: 10px;
            align-items: center;
            border-bottom: 1px solid rgba(224, 224, 224, 0.2);
            margin-bottom: 14px;
            padding-bottom: 6px;
        }

        .history-tab-btn {
            appearance: none;
            background: transparent;
            border: none;
            color: #d7d7d7;
            font-size: 14px;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            outline: none;
            transition: background .2s ease, color .2s ease;
            text-align: left;
            /* pastikan kiri */
        }

        .history-tab-btn:hover {
            background: rgba(224, 224, 224, 0.08);
            color: #fff;
        }

        .history-tab-btn.active {
            color: #e0a902;
            background: rgba(224, 169, 2, 0.08);
            position: relative;
        }

        .history-tab-btn.active::after {
            content: "";
            position: absolute;
            left: 12px;
            right: 12px;
            bottom: -7px;
            height: 2px;
            background: #e0a902;
            /* underline aktif */
            border-radius: 2px;
        }

        .history-content {
            margin-top: 10px;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        /* ====== LIST SPACING ====== */
        .booking-list {
            display: grid;
            grid-template-columns: 1fr;
            /* satu kolom = rapi kiri */
            row-gap: 12px;
            /* jarak antar kartu */
            margin: 0;
            /* nolkan margin liar */
            padding: 0;
        }

        /* ====== CARD ====== */
        .booking-card {
            background-color: #ffc107;
            border: 1px solid rgba(224, 224, 224, 0.18);
            border-radius: 12px;
            padding: 14px;
            color: #000;
            text-align: left;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
            box-shadow: 0 1px 4px rgba(224, 169, 2, 0.08);
        }

        .booking-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(224, 169, 2, 0.18);
            border-color: rgba(224, 169, 2, 0.28);
        }

        /* header card */
        .booking-header {
            display: grid;
            grid-template-columns: 1fr auto;
            /* judul kiri, status kanan */
            align-items: center;
            column-gap: 12px;
            margin-bottom: 10px;
        }

        .booking-header h4 {
            margin: 0;
            font-size: 15px;
            line-height: 1.3;
            color: #000;
            /* warna hitam untuk kontras dengan background kuning */
            font-weight: 700;
        }

        /* status pill */
        .booking-status {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #000;
            white-space: nowrap;
            /* biar ga turun baris */
        }

        .status-pending {
            background: #e0a902;
            color: #000;
        }

        .status-confirmed {
            background: #e0a902;
            color: #000;
        }

        .status-in-progress {
            background: #4A90E2;
            color: #fff;
        }

        .status-completed {
            background: #28a745;
            color: #fff;
        }

        .status-cancelled {
            background: #dc3545;
            color: #fff;
        }

        .status-unknown {
            background: #6c757d;
            color: #fff;
        }

        /* detail grid */
        .booking-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            /* dua kolom rapi */
            gap: 8px 12px;
        }

        @media (max-width: 520px) {
            .booking-details {
                grid-template-columns: 1fr;
            }

            /* mobile satu kolom */
        }

        .detail-item {
            display: grid;
            grid-template-columns: 18px 1fr;
            /* icon fixed, teks fleksibel */
            align-items: center;
            column-gap: 8px;
            font-size: 13px;
            color: #000;
            opacity: .92;
        }

        .detail-item i {
            color: #000;
        }

        /* pesan kosong */
        .no-booking {
            padding: 14px 0;
            color: #e0a902;
            text-align: left;
            font-size: 13px;
        }

        .no-booking i {
            font-size: 16px;
            margin-right: 6px;
            color: #e0a902;
            vertical-align: -2px;
        }
    </style>

    <script>
        // Ambil data booking dari API
        document.addEventListener('DOMContentLoaded', function() {
            loadBookingHistory();

            // Event listener untuk tab booking history
            const tabButtons = document.querySelectorAll('.history-tab-btn');
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.getAttribute('data-tab');

                    // Hapus class active dari semua button dan tab pane
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove(
                        'active'));

                    // Tambahkan class active ke button dan tab pane yang dipilih
                    this.classList.add('active');
                    document.getElementById(tabName).classList.add('active');
                });
            });
        });

        function loadBookingHistory() {
            // Ambil data booking mendatang dari API
            fetch('{{ route('user.booking.history', ['type' => 'upcoming']) }}')
                .then(response => response.json())
                .then(mendatangBookings => {
                    displayBookings(mendatangBookings, 'mendatang');
                })
                .catch(error => {
                    console.error('Error fetching upcoming bookings:', error);
                    document.getElementById('mendatangList').innerHTML = `
                        <div class="no-booking">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>Gagal memuat data booking</p>
                        </div>
                    `;
                });

            // Ambil data riwayat booking dari API
            fetch('{{ route('user.booking.history', ['type' => 'history']) }}')
                .then(response => response.json())
                .then(riwayatBookings => {
                    displayBookings(riwayatBookings, 'riwayat');
                })
                .catch(error => {
                    console.error('Error fetching booking history:', error);
                    document.getElementById('riwayatList').innerHTML = `
                        <div class="no-booking">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>Gagal memuat riwayat booking</p>
                        </div>
                    `;
                });
        }

        function displayBookings(bookings, tabType) {
            let bookingList;
            if (tabType === 'mendatang') {
                bookingList = document.getElementById('mendatangList');
            } else if (tabType === 'riwayat') {
                bookingList = document.getElementById('riwayatList');
            }

            if (!bookingList) return;

            if (Array.isArray(bookings) && bookings.length > 0) {
                bookingList.innerHTML = '';
                bookings.forEach(booking => {
                    const bookingCard = createBookingCard(booking);
                    bookingList.appendChild(bookingCard);
                });
            } else {
                if (tabType === 'mendatang') {
                    bookingList.innerHTML = `
                        <div class="no-booking">
                            <i class="fas fa-calendar-times"></i>
                            <p>Tidak Ada Booking Mendatang</p>
                            <p style="font-size: 0.9rem; margin-top: 5px;">Buat booking baru untuk layanan cukur</p>
                        </div>
                    `;
                } else if (tabType === 'riwayat') {
                    bookingList.innerHTML = `
                        <div class="no-booking">
                            <i class="fas fa-history"></i>
                            <p>Tidak Ada Riwayat Booking</p>
                        </div>
                    `;
                }
            }
        }

        function createBookingCard(booking) {
            const bookingCard = document.createElement('div');
            bookingCard.className = 'booking-card';

            // Format status menjadi teks yang mudah dimengerti
            let statusText = booking.status;
            let statusClass = '';
            switch (booking.status_raw) {
                case 'pending':
                    statusText = 'Menunggu';
                    statusClass = 'status-pending';
                    break;
                case 'confirmed':
                    statusText = 'Terkonfirmasi';
                    statusClass = 'status-confirmed';
                    break;
                case 'in_progress':
                    statusText = 'Sedang Berlangsung';
                    statusClass = 'status-in-progress';
                    break;
                case 'completed':
                    statusText = 'Selesai';
                    statusClass = 'status-completed';
                    break;
                case 'cancelled':
                    statusText = 'Dibatalkan';
                    statusClass = 'status-cancelled';
                    break;
                default:
                    statusText = booking.status;
                    statusClass = 'status-unknown';
            }

            // Format tanggal booking
            const bookingDate = new Date(booking.booking_date);
            const formattedDate = bookingDate.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });

            bookingCard.innerHTML = `
                <div class="booking-info">
                    <div class="booking-header">
                        <h4>${booking.service.name}</h4>
                        <span class="booking-status ${statusClass}">${statusText}</span>
                    </div>
                    <div class="booking-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span>${formattedDate}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <span>${booking.booking_time}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-user"></i>
                            <span>${booking.barber.name}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Rp ${booking.total_price?.toLocaleString('id-ID') || '0'}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-qrcode"></i>
                            <span>Kode: ${booking.booking_id}</span>
                        </div>
                    </div>
                </div>
            `;

            return bookingCard;
        }
    </script>

</body>

</html>
