document.addEventListener('DOMContentLoaded', function() {
    // --- Ambil Elemen DOM ---
    const btnNext = document.getElementById('btnNext');
    const timeSlotSection = document.querySelector('.time-slot-section');
    const timeSlotGrid = document.querySelector('.time-slot-grid');
    const calendarInput = document.getElementById('calendar-input');
    const timeSlotLoader = document.getElementById('time-slot-loader');

    // --- Variabel Status ---
    let selectedDate = null;
    let selectedTime = null;
    let selectedBarber = null; // Akan diambil dari session
    let fp = null; // Instance Flatpickr

    // --- Ambil data dari Session Storage ---
    const selectedServiceJSON = sessionStorage.getItem('selectedService');
    const selectedBarberJSON = sessionStorage.getItem('selectedBarber');

    if (!selectedServiceJSON || !selectedBarberJSON) {
        alert('Sesi tidak valid. Silahkan pilih layanan dan barber terlebih dahulu.');
        if (!selectedServiceJSON) {
            window.location.href = '/user/booking/service';
        } else {
            window.location.href = '/user/booking/barber';
        }
        return; // Hentikan eksekusi
    }

    // Parse data yang valid
    const selectedService = JSON.parse(selectedServiceJSON);
    selectedBarber = JSON.parse(selectedBarberJSON); // Simpan di variabel global skrip
    console.log("Layanan:", selectedService);
    console.log("Barber:", selectedBarber);


    /**
     * Memeriksa apakah tanggal dan waktu sudah dipilih, lalu mengaktifkan tombol 'Selanjutnya'.
     */
    function checkSelection() {
        btnNext.disabled = !(selectedDate && selectedTime);
    }

    /**
     * Menambahkan event listener ke semua tombol slot waktu yang baru dibuat.
     */
    function attachTimeSlotListeners() {
        const timeSlotButtons = document.querySelectorAll('.time-slot-button');
        
        timeSlotButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (this.disabled) return;
                
                // Hapus 'selected' dari semua tombol
                timeSlotButtons.forEach(b => b.classList.remove('selected'));
                
                // Tambahkan 'selected' ke tombol yang diklik
                this.classList.add('selected');
                selectedTime = this.textContent;
                console.log("Waktu Dipilih:", selectedTime);
                
                // Periksa kembali untuk mengaktifkan tombol 'Next'
                checkSelection();
            });
        });
    }

    /**
     * Gets available time slots from the backend API
     * @param {string} date - Selected date in YYYY-MM-DD format
     * @param {string} barberId - Selected barber ID
     */
    async function fetchAvailableTimeSlots(date, barberId) {
        console.log("Fetching available time slots for date:", date, "and barber:", barberId);
        
        try {
            const response = await fetch(`/user/booking/time-slots?date=${encodeURIComponent(date)}&barberId=${encodeURIComponent(barberId)}&serviceId=${encodeURIComponent(selectedService.id)}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log("Time slots received:", data);
            return data.availableSlots || [];

        } catch (error) {
            console.error("Error fetching time slots:", error);
            alert('Gagal memuat slot waktu. Silakan coba lagi nanti.');
            return [];
        }
    } 
    /**
     * Menghasilkan data ketersediaan palsu. 
     * Ganti ini dengan 'fetch' sungguhan.
     */
    function getMockAvailability(date, barberId) {
        console.log(`(Simulasi) Mengambil data untuk Barber ID: ${barberId} pada tanggal: ${date}`);
        const mockSlots = [
            "10:00", "10:45", "11:30", "12:15", "13:00", "13:45", "14:30", 
            "15:15", "16:00", "16:45", "17:30", "18:15", "19:00", "19:45", 
            "20:30", "21:15"
        ];
        
        const dateObj = fp.parseDate(date, "Y-m-d");
        const dayOfMonth = dateObj.getDate();
        const today = new Date();
        const selectedDay = new Date(date);

        return mockSlots.map((time, index) => {
            let isAvailable = true;

            // Simulasi ketersediaan yang berbeda tergantung tanggal
            if (dayOfMonth % 2 !== 0 && (index < 3 || index > 12)) {
                isAvailable = false;
            } else if (dayOfMonth % 2 === 0 && index >= 5 && index <= 9) {
                isAvailable = false;
            } else if (time === "12:15") {
                isAvailable = false; // Istirahat
            }

            // Disable slot yang sudah lewat jika tanggalnya hari ini
            if (selectedDay.toDateString() === today.toDateString()) {
                const [hours, minutes] = time.split(':').map(Number);
                const slotTime = new Date();
                slotTime.setHours(hours, minutes, 0, 0);
                if (slotTime.getTime() < today.getTime() + (15 * 60000)) { // +15 menit margin
                    isAvailable = false;
                }
            }

            return { time: time, available: isAvailable };
        });
    }


    /**
     * Memuat slot waktu yang tersedia dari backend (saat ini disimulasikan).
     * Ini adalah fungsi inti untuk integrasi backend.
     */
    function loadAvailableTimeSlots(date) {
        console.log("Memuat slot waktu untuk tanggal:", date);

        // Reset
        timeSlotGrid.innerHTML = ''; // Kosongkan grid
        timeSlotLoader.style.display = 'block'; // Tampilkan loader
        timeSlotSection.style.display = 'block'; // Tampilkan section (agar loader terlihat)
        selectedTime = null; // Reset waktu terpilih
        checkSelection(); // Disable tombol next

        // Fetch available time slots from the Laravel API - include serviceId for accurate duration calculation
        fetch(`/user/booking/time-slots?date=${date}&barberId=${selectedBarber.id}&serviceId=${selectedService.id}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Sembunyikan loader
            timeSlotLoader.style.display = 'none';

            if (data.availableSlots.length === 0) {
                timeSlotGrid.innerHTML = '<p style="color: var(--text-grey); text-align: center; grid-column: 1 / -1;">Tidak ada jadwal tersedia.</p>';
                return;
            }

            // Buat tombol untuk setiap slot waktu
            data.availableSlots.forEach(time => {
                const button = document.createElement('button');
                button.className = 'time-slot-button';
                button.textContent = time;
                button.disabled = false;
                timeSlotGrid.appendChild(button);
            });

            // Setelah semua tombol dibuat, tambahkan event listener
            attachTimeSlotListeners();

        })
        .catch(error => {
            console.error("Gagal memuat slot waktu:", error);
            timeSlotLoader.style.display = 'none';
            timeSlotGrid.innerHTML = '<p style="color: red; text-align: center; grid-column: 1 / -1;">Gagal memuat jadwal.</p>';
        });
    }

    // --- Inisialisasi Flatpickr (Kalender) ---
    fp = flatpickr(calendarInput, {
        inline: true,
        dateFormat: "Y-m-d",
        minDate: "today",
        monthSelectorType: "static",
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                selectedDate = dateStr;
                console.log("Tanggal Dipilih (Flatpickr):", selectedDate);
                
                // Panggil fungsi untuk memuat slot waktu dari backend
                loadAvailableTimeSlots(dateStr);
            }
        },
    });

    // --- Event Listener untuk Tombol 'Selanjutnya' ---
    btnNext.addEventListener('click', function() {
        if (selectedDate && selectedTime) {
            const selectedSchedule = { date: selectedDate, time: selectedTime };
            
            // Simpan data ke Session Storage
            sessionStorage.setItem('selectedSchedule', JSON.stringify(selectedSchedule));
            
            // Pindah ke halaman konfirmasi - Laravel route
            window.location.href = '/user/booking/confirmation';
        } else {
            alert('Mohon pilih tanggal dan waktu terlebih dahulu.');
        }
    });

    // --- Pengecekan Awal ---
    checkSelection();
});