document.addEventListener('DOMContentLoaded', function() {
    // --- Element Selectors ---
    const tabButtons = document.querySelectorAll('.history-tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    const userNameEl = document.getElementById('user-name');
    const userEmailEl = document.getElementById('user-email');
    const userPhoneEl = document.getElementById('user-phone');
    const totalBookingEl = document.getElementById('total-booking');
    const bookingSelesaiEl = document.getElementById('booking-selesai');
    const kuponAktifEl = document.getElementById('kupon-aktif');
    const totalBelanjaEl = document.getElementById('total-belanja');
    const loyaltyProgressEl = document.getElementById('loyalty-progress');
    const loyaltyProgressBarEl = document.getElementById('loyalty-progress-bar');
    const upcomingPane = document.getElementById('mendatang');
    const historyPane = document.getElementById('riwayat');
    // --- Logout Link ---
    const logoutLink = document.getElementById('logoutButton');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin keluar?')) {
                e.preventDefault();
            }
        });
    }

    // --- Tab Switching Logic ---
    tabButtons.forEach(button => {
        button.addEventListener('click', async () => { // Jadikan async untuk await
            // Deactivate all
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            
            // Activate clicked
            button.classList.add('active');
            const targetPaneId = button.getAttribute('data-tab');
            const targetPane = document.getElementById(targetPaneId);
            if (targetPane) {
                targetPane.classList.add('active');
                // --- BACKEND INTEGRATION ---
                // Muat data riwayat saat tab diklik
                // Konversi 'mendatang' ke 'upcoming' dan 'riwayat' ke 'history' untuk API
                const apiType = targetPaneId === 'mendatang' ? 'upcoming' : 'history';
                await fetchBookingHistory(targetPaneId); 
            }
        });
    });
    
    // --- Initial Load ---
    // Muat data profil dan tab aktif pertama kali (Booking Mendatang)
    loadProfile();
    
    /**
     * Fetches user profile data from the backend.
     * @returns {Promise<object>} A promise that resolves with user data.
     */
    async function fetchUserProfileData() {
        console.log("Fetching user profile from backend...");
        
        try {
            const response = await fetch('/user/profile/data', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error('Failed to fetch user profile');
            }

            const userData = await response.json();
            
            // Transform the response to match the expected format
            return {
                name: userData.name || userData.user?.name || 'N/A',
                email: userData.email || userData.user?.email || 'N/A',
                phone: userData.phone || userData.user?.phone || 'N/A',
                totalBooking: userData.totalBookings || 0,
                bookingSelesai: userData.completedBookings || 0,
                kuponAktif: userData.activeCoupons || 0,
                totalBelanja: userData.totalSpending || 0,
                loyaltyProgress: userData.loyaltyProgress || 0,
                loyaltyTarget: 5
            };
        } catch (error) {
            console.error("Error fetching user profile:", error);
            
            // Fallback: return mock data if API call fails
            return {
                name: "Jajal",
                email: "jajal@gmail.com",
                phone: "082141617181",
                totalBooking: 5, // Disesuaikan dengan data dummy
                bookingSelesai: 3, // Disesuaikan dengan data dummy
                kuponAktif: 0,
                totalBelanja: 155000, // Disesuaikan dengan data dummy
                loyaltyProgress: 3, // Disesuaikan (3 dari 5)
                loyaltyTarget: 5
            };
        }
    }
                    loyaltyTarget: 5    
                };
                console.log("Simulated data received:", mockUserData);
                resolve(mockUserData);
            }, 500); // 0.5 detik delay
        });
    }

    /**
     * Populates the HTML elements with user data.
     * @param {object} userData - The user data object fetched from the backend.
     */
    function displayUserData(userData) {
        userNameEl.textContent = userData.name || 'N/A';
        userEmailEl.textContent = userData.email || 'N/A';
        userPhoneEl.textContent = userData.phone || 'N/A';
        totalBookingEl.textContent = userData.totalBooking ?? 0;
        bookingSelesaiEl.textContent = userData.bookingSelesai ?? 0;
        kuponAktifEl.textContent = userData.kuponAktif ?? 0;
        totalBelanjaEl.textContent = `Rp ${(userData.totalBelanja ?? 0).toLocaleString('id-ID')}`;
        const progress = userData.loyaltyProgress ?? 0;
        const target = userData.loyaltyTarget ?? 5; 
        loyaltyProgressEl.textContent = `${progress}/${target}`;
        const progressPercent = target > 0 ? Math.min((progress / target) * 100, 100) : 0;
        loyaltyProgressBarEl.style.width = `${progressPercent}%`;
    }

    // --- DATA DUMMY BOOKING ---
    const dummyUpcomingBookings = [
        { 
            id: 'BK-789012', 
            serviceName: 'Cukur + Semir', 
            barberName: 'Barber B', 
            date: '2025-10-23', // Tanggal mendatang
            time: '14:30', 
            price: 80000,
            status: 'Dijadwalkan' 
        },
        { 
            id: 'BK-112233', 
            serviceName: 'Cukur + Kramas + Vitamin + Tonic', 
            barberName: 'Barber A', 
            date: '2025-11-05', // Tanggal mendatang
            time: '11:30', 
            price: 30000,
            status: 'Dijadwalkan' 
        }
    ];

    const dummyPastBookings = [
         { 
            id: 'BK-456789', 
            serviceName: 'Cukur + Hair Tonic', 
            barberName: 'Barber A', 
            date: '2025-10-15', // Tanggal lalu
            time: '10:00', 
            price: 20000,
            status: 'Selesai' 
        },
        { 
            id: 'BK-123456', 
            serviceName: 'Cukur + Kramas + Vitamin + Pijat', 
            barberName: 'Barber B', 
            date: '2025-09-28', // Tanggal lalu
            time: '16:00', 
            price: 45000,
            status: 'Selesai' 
        },
         { 
            id: 'BK-987654', 
            serviceName: 'Cukur + Hair Tonic', 
            barberName: 'Barber A', 
            date: '2025-08-10', // Tanggal lalu
            time: '19:00', 
            price: 20000,
            status: 'Dibatalkan' // Contoh status dibatalkan
        },
         { 
            id: 'BK-654321', 
            serviceName: 'Hairlight', // Contoh layanan berbeda
            barberName: 'Barber B', 
            date: '2025-07-01', // Tanggal lalu
            time: '13:00', 
            price: 175000,
            status: 'Selesai' 
        }
    ];
    // -------------------------

    /**
     * Format tanggal ke format Indonesia (misal: Selasa, 21 Oktober 2025)
     * @param {string} dateString - Tanggal dalam format YYYY-MM-DD
     * @returns {string} Tanggal yang diformat atau string asli jika error
     */
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            const dateObj = new Date(dateString + 'T00:00:00'); 
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return dateObj.toLocaleDateString('id-ID', options);
        } catch (e) {
            return dateString; // Fallback
        }
    }

     /**
     * Membuat elemen HTML untuk satu item booking.
     * @param {object} booking - Objek data booking.
     * @returns {HTMLElement} Elemen div yang berisi detail booking.
     */
     function renderBookingItem(booking) {
        console.log("Rendering booking item:", booking);
        
        const itemDiv = document.createElement('div');
        itemDiv.style.border = '1px solid var(--bg-light-dark)';
        itemDiv.style.padding = '1rem';
        itemDiv.style.marginBottom = '1rem';
        itemDiv.style.borderRadius = '5px';
        itemDiv.style.backgroundColor = 'var(--bg-dark)'; // Background item sedikit beda

        // Tentukan warna status berdasarkan status_raw
        let statusColor = 'var(--text-grey)';
        if (booking.status_raw === 'completed') {
            statusColor = 'var(--color-success)';
        } else if (booking.status_raw === 'confirmed') {
            statusColor = 'var(--accent-gold)';
        } else if (booking.status_raw === 'pending') {
            statusColor = 'var(--accent-gold)';
        } else if (booking.status_raw === 'cancelled') {
            statusColor = '#dc3545'; // Merah untuk batal
        }

        itemDiv.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <strong style="color: var(--text-light); font-size: 1.1rem;">${booking.service}</strong>
                <span style="font-size: 0.9rem; font-weight: bold; color: ${statusColor};">${booking.status}</span>
            </div>
            <p style="font-size: 0.9rem; color: var(--text-grey); margin: 0.2rem 0;">
                <i class="fas fa-calendar-alt" style="color: var(--accent-gold); margin-right: 5px;"></i> ${formatDate(booking.date)} | 
                <i class="fas fa-clock" style="color: var(--accent-gold); margin-left: 5px; margin-right: 5px;"></i> ${booking.time}
            </p>
            <p style="font-size: 0.9rem; color: var(--text-grey); margin: 0.2rem 0;">
                <i class="fas fa-user" style="color: var(--accent-gold); margin-right: 5px;"></i> Barber: ${booking.barber}
            </p>
             <p style="font-size: 0.9rem; color: var(--text-grey); margin: 0.2rem 0;">
                <i class="fas fa-tag" style="color: var(--accent-gold); margin-right: 5px;"></i> ID: ${booking.booking_id} | Harga: Rp ${parseInt(booking.price || 0).toLocaleString('id-ID')}
            </p>
            ${(booking.status_raw === 'pending' || booking.status_raw === 'confirmed') ? 
                '<button class="cancel-booking-btn" data-booking-id="' + booking.booking_id + '" style="margin-top: 0.5rem; background: #dc3545; color: white; border: none; padding: 5px 10px; font-size: 0.8rem; border-radius: 3px; cursor: pointer;">Batalkan</button>' 
                : ''}
        `;
        
        // Tambahkan event listener untuk tombol batal jika ada
        const cancelButton = itemDiv.querySelector('.cancel-booking-btn');
        if (cancelButton) {
            cancelButton.addEventListener('click', () => {
                handleCancelBooking(cancelButton.getAttribute('data-booking-id'));
            });
        }

        return itemDiv;
    }
    
    /**
     * Menangani logika pembatalan booking.
     * @param {string} bookingId - ID booking yang akan dibatalkan.
     */
    async function handleCancelBooking(bookingId) {
        if (!bookingId) return;

        if (confirm(`Apakah Anda yakin ingin membatalkan booking ${bookingId}?`)) {
            console.log(`Membatalkan booking: ${bookingId}`);
            
            try {
                const response = await fetch(`/admin/bookings/${bookingId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: 'cancelled' })
                });
                
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Gagal membatalkan');
                }
                
                const result = await response.json();
                alert('Booking berhasil dibatalkan.');
                
                // Muat ulang data profil & riwayat
                loadProfile(); 
                
                // Secara spesifik muat ulang tab yang sedang aktif
                const activeTabId = document.querySelector('.history-tab-btn.active').getAttribute('data-tab');
                fetchBookingHistory(activeTabId);
                
            } catch (error) {
                console.error("Gagal membatalkan booking:", error);
                alert('Gagal membatalkan booking: ' + error.message);
            }
        }
    }


    /**
     * Fetches booking history based on type (upcoming/past).
     * @param {string} type - 'mendatang' or 'riwayat'.
     */
    async function fetchBookingHistory(type) {
        console.log(`Fetching booking history from backend: ${type}`);
        const targetPane = document.getElementById(type);
        if (!targetPane) {
            console.error(`Target pane with ID '${type}' not found`);
            return;
        }

        targetPane.innerHTML = '<p style="text-align:center; color: var(--text-grey);">Memuat riwayat...</p>'; 

        try {
            // Konversi tipe UI ke tipe API
            const apiType = type === 'mendatang' ? 'upcoming' : 'history';
            const url = `/user/booking/history?type=${apiType}`;
            
            console.log(`Fetching from URL: ${url}`);
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            console.log(`Response status: ${response.status}`);

            if (!response.ok) {
                const errorText = await response.text();
                console.error(`HTTP Error ${response.status}: ${errorText}`);
                throw new Error(`Failed to fetch booking history: ${response.status} ${errorText}`);
            }

            const bookings = await response.json();
            console.log(`Received ${bookings.length} bookings:`, bookings);
            
            if (bookings.length > 0) {
                targetPane.innerHTML = ''; // Clear loading message
                
                bookings.forEach(booking => {
                    const bookingElement = renderBookingItem({
                        id: booking.id,
                        service: booking.service?.name || 'N/A',
                        barber: booking.barber?.name || 'N/A',
                        date: booking.booking_date,
                        time: booking.booking_time,
                        status: booking.status,
                        status_raw: booking.status_raw || booking.status, // Tambahkan status_raw untuk logika pembatalan
                        price: booking.total_price,
                        booking_id: booking.booking_id || booking.id // Tambahkan booking_id
                    });
                    targetPane.appendChild(bookingElement);
                });
            } else {
                // Tampilkan pesan 'no-booking' jika data kosong
                if (type === 'mendatang') {
                    targetPane.innerHTML = `
                        <div class="no-booking">
                             <i class="fas fa-calendar-times"></i>
                             <p>Tidak Ada Booking Mendatang</p>
                             <p style="font-size: 0.9rem; margin-top: 5px;">Buat booking baru untuk layanan cukur</p>
                        </div>`;
                } else {
                    targetPane.innerHTML = `
                        <div class="no-booking">
                              <i class="fas fa-history"></i>
                             <p>Tidak Ada Riwayat Booking</p>
                        </div>`;
                }
            }
        } catch (error) {
            console.error("Error fetching booking history:", error);
            targetPane.innerHTML = `<p style="text-align:center; color: var(--button-danger);">Gagal memuat riwayat booking: ${error.message}</p>`;
        }
    }
async function handleLogout() {
        if (!confirm('Apakah Anda yakin ingin keluar?')) {
            return; // Batalkan jika pengguna memilih "Cancel"
        }

        console.log("Processing logout...");
        logoutButton.disabled = true;
        logoutButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Keluar...'; // Tampilkan ikon loading

        try {
            const response = await fetch('/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error('Logout failed');
            }
            
            // Jika backend berhasil logout (menghapus session/token),
            // kita bisa membersihkan data sisi klien & redirect.
            sessionStorage.clear(); // Hapus semua data sessionStorage
            localStorage.clear(); // Mungkin juga perlu menghapus localStorage jika dipakai
            window.location.href = '/login'; // Redirect ke halaman login
        } catch (error) {
            console.error("Logout error:", error);
            alert('Terjadi kesalahan saat logout. Silakan coba lagi.');
            logoutButton.disabled = false; // Aktifkan kembali tombol jika gagal
            logoutButton.innerHTML = '<i class="fas fa-sign-out-alt"></i> Keluar';
        }
    }

    // Tambahkan event listener ke tombol logout
    if (logoutButton) { // Pastikan tombol ada
        logoutButton.addEventListener('click', handleLogout);
    }

    // --- Initial Load ---
    async function loadProfile() {
        try {
            // Set elemen ke loading state awal
            userNameEl.textContent = 'Memuat...';
            userEmailEl.textContent = '...';
            userPhoneEl.textContent = '...';
            totalBookingEl.textContent = '...';
            // ... (set elemen lain ke loading state jika perlu) ...
            upcomingPane.innerHTML = '<p style="text-align:center; color: var(--text-grey);">Memuat...</p>'; 
            historyPane.innerHTML = '<p style="text-align:center; color: var(--text-grey);">Memuat...</p>'; 

            // Ambil data profil
            const userData = await fetchUserProfileData();
            displayUserData(userData);

            // Muat data riwayat untuk tab yang aktif pertama kali (mendatang)
            await fetchBookingHistory('mendatang'); 

        } catch (error) {
            console.error("Gagal memuat data profil:", error);
            userNameEl.textContent = "Gagal Memuat";
             userEmailEl.textContent = "-";
             userPhoneEl.textContent = "-";
             // ... handle error display for other elements ...
             upcomingPane.innerHTML = '<p style="text-align:center; color: red;">Gagal memuat data.</p>'; 
             historyPane.innerHTML = '<p style="text-align:center; color: red;">Gagal memuat data.</p>'; 
        }
    }

    loadProfile(); // Load profile data when the page is ready
});