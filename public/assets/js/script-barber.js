document.addEventListener('DOMContentLoaded', function() {
    // Dapatkan semua elemen card barber yang dihasilkan (oleh backend)
    const barberCards = document.querySelectorAll('.barber-card-select');
    const btnNext = document.getElementById('btnNext');
    let selectedBarber = null;

    // Ambil data layanan dari sessionStorage
    const selectedServiceJSON = sessionStorage.getItem('selectedService');
    if (!selectedServiceJSON) {
        // Jika tidak ada data layanan, redirect kembali ke halaman layanan
        alert('Pilih layanan terlebih dahulu.');
        window.location.href = '/user/booking/service';
        return; // Hentikan eksekusi script
    }
    
    // Baris ini opsional, hanya untuk debugging di console browser
    const selectedService = JSON.parse(selectedServiceJSON);
    console.log("Layanan yang dipilih:", selectedService); 

    // Tambahkan event listener untuk setiap card barber
    barberCards.forEach(card => {
        card.addEventListener('click', function() {
            // Hapus kelas 'selected' dari semua card
            barberCards.forEach(bc => bc.classList.remove('selected'));
            
            // Tambahkan kelas 'selected' ke card yang diklik
            this.classList.add('selected');
            
            // Simpan data barber yang dipilih (ID dan Nama)
            // Ini adalah perubahan penting untuk backend
            selectedBarber = {
                id: this.dataset.barberId,      // Ambil ID dari data-barber-id
                name: this.dataset.barberName   // Ambil Nama dari data-barber-name
            };
            
            // Aktifkan tombol 'Selanjutnya'
            btnNext.disabled = false;
        });
    });

    // Tambahkan event listener untuk tombol 'Selanjutnya'
    btnNext.addEventListener('click', function() {
        if (selectedBarber) {
            // Simpan objek barber yang dipilih ke sessionStorage
            sessionStorage.setItem('selectedBarber', JSON.stringify(selectedBarber));
            
            // Arahkan ke halaman Pilih Jadwal - Laravel route
            window.location.href = '/user/booking/schedule'; 
        } else {
            // Seharusnya tidak terjadi jika tombolnya disabled, tapi sebagai penjagaan
            alert('Mohon pilih barber terlebih dahulu.');
        }
    });
});