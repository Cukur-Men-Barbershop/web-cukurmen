document.addEventListener('DOMContentLoaded', function() {
    // --- Ambil Elemen DOM ---
    const cancelButton = document.getElementById('cancelButton');
    const qrCodeContainer = document.getElementById('qr-code-container');
    const bookingIdEl = document.getElementById('booking-id');
    
    // --- Ambil Booking ID dari DOM ---
    const bookingId = bookingIdEl ? bookingIdEl.textContent.trim() : null;
    
    // --- Generate QR Code ---
    if (qrCodeContainer && bookingId) {
        // Buat elemen img untuk QR code
        const qrImg = document.createElement('img');
        qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(bookingId)}`;
        qrImg.alt = `QR Code for Booking ${bookingId}`;
        qrImg.style.width = '100%';
        qrImg.style.height = '100%';
        qrCodeContainer.innerHTML = ''; // Hapus konten sebelumnya
        qrCodeContainer.appendChild(qrImg);
    }

    /**
     * Mengirim permintaan pembatalan ke backend
     * @param {string} id - Booking ID yang akan dibatalkan
     */
    async function cancelBooking(id) {
        if (!id) {
            alert('Booking ID tidak valid.');
            return;
        }

        // Tampilkan konfirmasi sebelum membatalkan
        if (!confirm('Apakah Anda yakin ingin membatalkan booking ini?')) {
            return;
        }

        console.log(`Mengirim permintaan pembatalan untuk Booking ID: ${id}`);
        cancelButton.disabled = true;
        cancelButton.textContent = 'Membatalkan...';

        try {
            const response = await fetch(`/admin/bookings/${id}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: 'cancelled' })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Gagal menghubungi server.');
            }

            const result = await response.json();
            alert('Booking berhasil dibatalkan.');
            
            // Redirect ke halaman service setelah pembatalan
            window.location.href = '/user/booking/service';

        } catch (error) {
            console.error("Gagal membatalkan booking:", error);
            alert('Gagal membatalkan booking. Coba lagi nanti.');
            cancelButton.disabled = false;
            cancelButton.textContent = 'Batalkan Booking';
        }
    }

    // --- Event listener untuk tombol Batalkan Booking ---
    if (cancelButton && bookingId) {
        cancelButton.addEventListener('click', function() {
            cancelBooking(bookingId);
        });
    }
});