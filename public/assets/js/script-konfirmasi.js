document.addEventListener('DOMContentLoaded', function() {
    // --- Ambil Elemen DOM ---
    const serviceNameEl = document.getElementById('service-name');
    const serviceDurationEl = document.getElementById('service-duration');
    const barberNameEl = document.getElementById('barber-name');
    const scheduleDateEl = document.getElementById('schedule-date');
    const scheduleTimeEl = document.getElementById('schedule-time');
    const totalPriceEl = document.getElementById('total-price');
    const btnConfirm = document.getElementById('btnConfirm');

    let bookingData = {}; // Objek untuk menyimpan semua data booking

    // --- Ambil data dari sessionStorage ---
    const selectedServiceJSON = sessionStorage.getItem('selectedService');
    const selectedBarberJSON = sessionStorage.getItem('selectedBarber');
    const selectedScheduleJSON = sessionStorage.getItem('selectedSchedule');

    // --- Validasi Data ---
    // Redirect jika salah satu data penting tidak ada
    if (!selectedServiceJSON || !selectedBarberJSON || !selectedScheduleJSON) {
        alert('Data booking tidak lengkap. Silahkan ulangi proses booking.');
        window.location.href = '/user/booking/service'; 
        return; 
    }

    // --- Parsing dan Tampilkan Data ---
    try {
        const service = JSON.parse(selectedServiceJSON);
        const barber = JSON.parse(selectedBarberJSON);
        const schedule = JSON.parse(selectedScheduleJSON);

        // Kumpulkan data untuk dikirim ke backend
        bookingData = {
            serviceId: service.id,
            barberId: barber.id,
            date: schedule.date,
            time: schedule.time,
            totalPrice: parseInt(service.price) || 0,
            duration: parseInt(service.duration) || 0,
            paymentMethod: 'Bayar di Tempat', // Hardcode untuk alur ini
            phone: '' // Nomor HP akan diisi sebelum submit
        };
        
        // Add CSRF token to bookingData for submission
        bookingData._token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // --- Tampilkan data di halaman ---
        serviceNameEl.textContent = service.name || 'N/A';
        serviceDurationEl.textContent = service.duration ? `${service.duration} menit` : 'N/A';
        barberNameEl.textContent = barber.name || 'N/A';
        
        // Format tanggal (contoh: "Selasa, 21 Oktober 2025")
        try {
            // Tambahkan T00:00:00 untuk memastikan parsing zona waktu lokal yang benar
            const dateObj = new Date(schedule.date + 'T00:00:00'); 
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            scheduleDateEl.textContent = dateObj.toLocaleDateString('id-ID', options);
        } catch (e) {
            scheduleDateEl.textContent = schedule.date || 'N/A'; // Fallback jika format tanggal salah
        }
        
        scheduleTimeEl.textContent = schedule.time || 'N/A';

        // Tampilkan total harga
        totalPriceEl.textContent = `Rp ${bookingData.totalPrice.toLocaleString('id-ID')}`;

    } catch (error) {
        console.error("Error parsing booking data:", error);
        alert('Terjadi kesalahan saat memuat data booking.');
        window.location.href = '/user/booking/service';
        return;
    }

    /**
     * Fungsi untuk mengirim data booking ke backend.
     * @param {object} data - Objek bookingData yang akan dikirim.
     */
    async function submitBooking(data) {
        console.log("Mengirim data booking ke backend:", data);

        // --- TITIK INTEGRASI BACKEND ---
        // Ganti bagian 'setTimeout' ini dengan 'fetch' call sungguhan
        
        try {
            const response = await fetch('/user/booking/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    service_id: data.serviceId,
                    barber_id: data.barberId,
                    booking_date: data.date,
                    booking_time: data.time,
                    phone: data.phone
                })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Gagal membuat booking.');
            }

            const result = await response.json(); // Data booking yang sudah dikonfirmasi
            console.log("Respons dari backend:", result);
            
            // Arahkan ke halaman detail dengan ID booking
            if (result.booking_id) {
                window.location.href = `/user/booking/detail?id=${result.booking_id}`;
            } else {
                // Fallback jika tidak ada booking_id
                window.location.href = '/user/booking/detail';
            }
            
            // Hapus data booking sebelumnya agar alur bersih
            sessionStorage.removeItem('selectedService');
            sessionStorage.removeItem('selectedBarber');
            sessionStorage.removeItem('selectedSchedule');

        } catch (error) {
            console.error("Error saat submit booking:", error);
            alert(`Terjadi kesalahan: ${error.message}`);
            // Aktifkan kembali tombol jika gagal
            btnConfirm.disabled = false;
            btnConfirm.textContent = 'Konfirmasi Booking';
        }
    }


    // --- Event listener untuk tombol Konfirmasi Booking ---
    btnConfirm.addEventListener('click', async function() {
        // Nonaktifkan tombol untuk mencegah klik ganda
        btnConfirm.disabled = true;
        btnConfirm.textContent = 'Memproses...';

        // Ambil nomor HP dari input
        const phoneInput = document.getElementById('customer-phone');
        if (!phoneInput.value.trim()) {
            alert('Mohon masukkan nomor WhatsApp Anda.');
            btnConfirm.disabled = false;
            btnConfirm.textContent = 'Konfirmasi Booking';
            phoneInput.focus();
            return;
        }
        
        // Tambahkan nomor HP ke data booking
        bookingData.phone = phoneInput.value.trim();

        // Panggil fungsi submit
        // (Gunakan `await` jika mengganti simulasi dengan fetch asli)
        await submitBooking(bookingData);
    });

});