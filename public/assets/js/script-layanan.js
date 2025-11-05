document.addEventListener('DOMContentLoaded', function() {
    const layananCards = document.querySelectorAll('.layanan-card-full');
    const btnNext = document.getElementById('btnNext');
    let selectedService = null;

    layananCards.forEach(card => {
        card.addEventListener('click', function() {
            // Hapus 'selected' dari semua card
            layananCards.forEach(lc => lc.classList.remove('selected'));
            
            // Tambahkan 'selected' ke card yang diklik
            this.classList.add('selected');
            
            // Simpan data layanan dari data-attributes
            // Ini adalah cara yang lebih bersih dan backend-friendly
            selectedService = {
                id: this.dataset.serviceId,          // Mengambil 'data-service-id'
                name: this.dataset.serviceName,      // Mengambil 'data-service-name'
                price: this.dataset.price,           // Mengambil 'data-price'
                duration: this.dataset.duration      // Mengambil 'data-duration'
            };
            
            // Aktifkan tombol 'Selanjutnya'
            btnNext.disabled = false;
        });
    });

    btnNext.addEventListener('click', function() {
        if (selectedService) {
            // Simpan objek layanan yang dipilih ke sessionStorage
            sessionStorage.setItem('selectedService', JSON.stringify(selectedService));
            
            // Arahkan ke halaman selanjutnya (Pilih Barber) - Laravel route
            window.location.href = "/user/booking/barber"; 
        } else {
            // Ini sebagai penjagaan, meskipun tombolnya seharusnya disabled
            alert('Mohon pilih layanan terlebih dahulu.');
        }
    });
});