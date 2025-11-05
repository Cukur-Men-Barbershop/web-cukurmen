# Panduan Penggunaan Aplikasi CUKURMEN

## Gambaran Umum
CUKURMEN adalah aplikasi berbasis web yang dirancang untuk mengelola sistem pemesanan (booking) layanan cukur profesional. Aplikasi ini memungkinkan pelanggan untuk memesan layanan cukur secara online serta menyediakan dashboard admin untuk mengelola booking, jadwal, laporan, dan produk barbershop.

## Alur Kerja Aplikasi

### 1. Halaman Awal (Landing Page)
- **File:** `landingpage.blade.php`
- Pengguna (admin atau user) akan diarahkan ke halaman `landingpage.blade.php` sebagai halaman pertama.
- Di bagian bawah halaman, terdapat tombol "Login / Daftar" yang akan mengarahkan ke `login.blade.php`.

### 2. Sistem Otentikasi (Login/Registrasi)
- **File:** `login.blade.php`
- Pengguna dapat masuk ke akun mereka atau mendaftar akun baru.
- Saat mendaftar, role default yang diberikan adalah "user", bukan admin.
- Untuk menjadi admin, role pengguna harus diubah secara manual di database dari "user" menjadi "admin".
- Setelah login:
  - Jika role pengguna adalah admin, akan diarahkan ke `resources/view/admin/admindashboard.blade.php`
  - Jika role pengguna adalah user, akan diarahkan ke sistem booking `resources/view/user/layanan.blade.php`

### 3. Tampilan User Setelah Login
- Setelah login, pengguna mendapat akses ke 3 navbar utama: **Booking**, **Product**, dan **Profil**
- Halaman pertama yang ditampilkan adalah sistem booking

#### a. Proses Booking (Booking Flow)
- **File:** `resources/view/user/layanan.blade.php` → `resources/view/user/barber.blade.php` → `resources/view/user/jadwal.blade.php` → `resources/view/user/konfirmasi.blade.php` → `resources/view/user/detail.blade.php`
- Langkah-langkah booking:
  1. **Pilih Layanan** (`layanan.blade.php`): Pengguna memilih jenis layanan cukur
  2. **Pilih Barber** (`barber.blade.php`): Pengguna memilih barber yang tersedia
  3. **Pilih Jadwal** (`jadwal.blade.php`): Pengguna memilih tanggal dan waktu yang tersedia
  4. **Konfirmasi Booking** (`konfirmasi.blade.php`): Pengguna melihat ringkasan booking sebelum dikonfirmasi
  5. **Detail Booking** (`detail.blade.php`): Setelah booking berhasil, ditampilkan detail booking termasuk QR Code

#### b. Halaman Produk
- **File:** `user/produk.blade.php`
- Menampilkan katalog produk perawatan rambut yang tersedia
- Pembelian produk hanya dapat dilakukan langsung di barbershop

#### c. Profil Pengguna
- **File:** `user/profil.blade.php`
- Menampilkan informasi profil pengguna
- Menampilkan status booking dan riwayat booking
- Menampilkan progress program loyalitas

### 4. Tampilan Admin
- **File:** `admin/admindashboard.blade.php`
- Hanya dapat diakses jika role pengguna adalah admin
- Menyediakan dashboard komprehensif untuk:
  - Manajemen produk
  - Manajemen profil pengguna
  - Manajemen admin
  - Sistem check-in pelanggan
  - Manajemen walk-in booking
  - Penjadwalan
  - Laporan dan analisis

## Struktur JavaScript

### File JavaScript Utama:
- `/assets/js/script-login.js` - Menangani logika login dan registrasi
- `/assets/js/script-layanan.js` - Menangani pemilihan layanan
- `/assets/js/script-barber.js` - Menangani pemilihan barber
- `/assets/js/script-jadwal.js` - Menangani pemilihan jadwal dan slot waktu
- `/assets/js/script-konfirmasi.js` - Menangani konfirmasi booking
- `/assets/js/script-detail.js` - Menangani detail booking dan QR Code
- `/assets/js/script-profil.js` - Menangani data profil dan riwayat booking

### Fungsi Umum JavaScript:
- Validasi form otentikasi dan booking
- Pengelolaan state seleksi layanan, barber, dan jadwal
- Integrasi dengan sistem manajemen booking
- Menyimpan data sementara selama proses booking
- Menangani logout dan navigasi antar halaman
- Integrasi dengan API backend untuk pengambilan data

## Skema Database MySQL

```sql
-- Tabel pengguna
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel layanan
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    duration INT NOT NULL, -- durasi dalam menit
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel barber
CREATE TABLE barbers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    expertise TEXT,
    rating DECIMAL(3, 2) DEFAULT 0.00,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel produk
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel booking
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    barber_id INT NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    booking_status ENUM('pending', 'confirmed', 'check-in', 'completed', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'unpaid') DEFAULT 'pending',
    booking_type ENUM('online', 'walk-in') DEFAULT 'online',
    booking_code VARCHAR(10) UNIQUE NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (barber_id) REFERENCES barbers(id)
);

-- Tabel riwayat booking
CREATE TABLE booking_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    status_before VARCHAR(50),
    status_after VARCHAR(50),
    change_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- Tabel program loyalitas
CREATE TABLE loyalty_program (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    booking_count INT DEFAULT 0,
    points INT DEFAULT 0,
    coupon_count INT DEFAULT 0,
    total_spent DECIMAL(12, 2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel slot jadwal tersedia
CREATE TABLE schedule_slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    barber_id INT NOT NULL,
    date DATE NOT NULL,
    time_slot TIME NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (barber_id) REFERENCES barbers(id) ON DELETE CASCADE
);

-- Tabel produk yang dibeli (opsional, jika fitur e-commerce akan dikembangkan)
CREATE TABLE product_purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

## Konfigurasi dan Penyesuaian

### Variabel Lingkungan (Opsional)
Untuk menyesuaikan aplikasi, Anda dapat membuat file konfigurasi dengan variabel penting seperti:
- Koneksi database
- API keys untuk layanan eksternal
- Parameter sistem lainnya

### Fitur-fitur Khusus
- Sistem reminder WhatsApp otomatis
- Program loyalitas (5x cukur = 1x gratis)
- Sistem QR Code untuk verifikasi booking
- Dashboard real-time untuk admin
- Sistem walk-in booking untuk pelanggan datang langsung
- Laporan dan analisis performa barber serta layanan

## Panduan Pengembangan dan Pemeliharaan

### Untuk Admin Sistem:
1. Jalankan database MySQL dan pastikan semua tabel tersedia
2. Untuk membuat akun admin, daftarkan akun baru melalui halaman registrasi, lalu ubah role dari "user" ke "admin" di database
3. Gunakan dashboard admin untuk mengelola layanan, barber, dan booking

### Untuk Pengembang:
1. Pastikan struktur folder dan file sesuai dengan yang disediakan
2. Sesuaikan endpoint API pada file JavaScript dengan backend Anda
3. Lakukan uji coba pada semua proses booking untuk memastikan alur kerja berjalan lancar
4. Implementasikan backend untuk menangani semua permintaan dari frontend

## Penutup
CUKURMEN dirancang untuk meningkatkan efisiensi operasional barbershop dan memberikan pengalaman booking yang nyaman bagi pelanggan. Dengan sistem manajemen digital, barbershop dapat mengelola jadwal, pelanggan, dan layanan dengan lebih efektif.