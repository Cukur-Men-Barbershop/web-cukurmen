//Pesan Reminder Booking (Sebelum Waktu Cukur)
``
document.getElementById("btn-reminder").addEventListener("click", function(event) {
  event.preventDefault();

  const nomorTujuan = '6285174059330';
  const namaPelanggan = document.getElementById("namaPelanggan").value;
  const tanggalBooking = document.getElementById("tanggalBooking").value;
  const jamBooking = document.getElementById("jamBooking").value;
  const namaBarber = document.getElementById("namaBarber").value;

  const isiPesan = `
Halo, Kak ${namaPelanggan}! ✂️

Ini adalah *pengingat booking* dari *CUKURMEN*. Jangan lupa, Kakak punya jadwal cukur:

📅 Tanggal: ${tanggalBooking}
⏰ Jam: ${jamBooking}
💈 Barberman: ${namaBarber}

Mohon datang tepat waktu ya agar antrian tetap nyaman 😊.
Jika berhalangan hadir, Kakak bisa reschedule melalui aplikasi CUKURMEN.

📍 Alamat CUKURMEN Barbershop:
https://maps.app.goo.gl/nQEVzTvmseKvNvNP6

Sampai ketemu di kursi cukur terbaik kami! 💇‍♂️🔥
`;

  const url = `https://api.whatsapp.com/send?phone=${nomorTujuan}&text=${encodeURIComponent(isiPesan)}`;
  window.open(url, "_blank");
});

``

//Pesan Struk dengan Link Rating
``
document.getElementById("btn-struk").addEventListener("click", function(event) {
  event.preventDefault();

  const nomorTujuan = '6285174059330';
  const namaPelanggan = document.getElementById("namaPelanggan").value;
  const kodeBooking = document.getElementById("kodeBooking").value;
  const namaService = document.getElementById("namaService").value;
  const namaBarber = document.getElementById("namaBarber").value;
  const durasi = document.getElementById("durasi").value;
  const metodePembayaran = document.getElementById("metodePembayaran").value;
  const totalHarga = document.getElementById("totalHarga").value;

  const isiPesan = `
Halo, Kak ${namaPelanggan}! Terima kasih sudah cukur di *CUKURMEN* 💈

Berikut adalah *struk pembayaran* Kakak:

🧾 Detail Transaksi:
• ID Booking: ${kodeBooking}
• Layanan: ${namaService}
• Barberman: ${namaBarber}
• Durasi: ${durasi} menit
• Metode Pembayaran: ${metodePembayaran}
• Total: Rp${totalHarga}

Kami harap Kakak puas dengan hasilnya 😊  
👉 Silakan beri rating dan ulasan singkat kami di Google Maps:  
https://maps.app.goo.gl/nQEVzTvmseKvNvNP6

Terima kasih banyak atas kunjungannya — sampai ketemu lagi di CUKURMEN! 😎🔥
`;

  const url = `https://api.whatsapp.com/send?phone=${nomorTujuan}&text=${encodeURIComponent(isiPesan)}`;
  window.open(url, "_blank");
});
``