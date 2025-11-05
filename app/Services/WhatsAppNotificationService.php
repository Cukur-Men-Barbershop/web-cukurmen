<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    private $whatsappApiUrl;
    private $whatsappApiKey;
    private $whatsappSenderNumber;

    public function __construct()
    {
        // Konfigurasi API WhatsApp
        $this->whatsappApiUrl = env('WHATSAPP_API_URL', 'https://api.whatsapp.com/v1');
        $this->whatsappApiKey = env('WHATSAPP_API_KEY');
        $this->whatsappSenderNumber = env('WHATSAPP_SENDER_NUMBER');
    }

    /**
     * Kirim reminder 10 menit sebelum jadwal cukur
     */
    public function sendBookingReminder(Booking $booking)
    {
        // Format waktu reminder (10 menit sebelum jadwal)
        $bookingDateTime = \DateTime::createFromFormat('Y-m-d H:i', $booking->booking_date . ' ' . $booking->booking_time);
        $reminderTime = $bookingDateTime->modify('-10 minutes')->format('H:i');
        
        $message = "Halo {$booking->user->name}!\n\n";
        $message .= "Ini adalah reminder untuk booking Anda:\n";
        $message .= "Layanan: {$booking->service->name}\n";
        $message .= "Barber: {$booking->barber->name}\n";
        $message .= "Tanggal: " . $bookingDateTime->format('d M Y') . "\n";
        $message .= "Waktu: {$booking->booking_time}\n\n";
        $message .= "Silakan datang 5 menit sebelumnya untuk persiapan.\n";
        $message .= "Terima kasih telah memilih Cukur Men!";

        return $this->sendWhatsAppMessage($booking->phone, $message);
    }

    /**
     * Kirim struk setelah selesai cukur
     */
    public function sendBookingReceipt(Booking $booking)
    {
        $message = "Halo {$booking->user->name}!\n\n";
        $message .= "Berikut adalah struk booking Anda:\n";
        $message .= "ID Booking: {$booking->booking_id}\n";
        $message .= "Layanan: {$booking->service->name}\n";
        $message .= "Barber: {$booking->barber->name}\n";
        $message .= "Tanggal: {$booking->booking_date}\n";
        $message .= "Waktu: {$booking->booking_time}\n";
        $message .= "Total Bayar: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n\n";
        $message .= "Terima kasih telah menggunakan layanan kami. Sampai jumpa di kunjungan berikutnya!";

        return $this->sendWhatsAppMessage($booking->phone, $message);
    }

    /**
     * Kirim pesan WhatsApp menggunakan API
     */
    private function sendWhatsAppMessage($phoneNumber, $message)
    {
        // Validasi konfigurasi
        if (!$this->whatsappApiKey || !$this->whatsappSenderNumber) {
            Log::warning('WhatsApp API credentials not configured');
            return false;
        }

        try {
            // Untuk contoh ini, kita akan menggunakan HTTP client untuk mengirim pesan
            // Anda perlu mengganti ini dengan implementasi sesungguhnya berdasarkan API WhatsApp yang Anda gunakan
            
            // Contoh untuk Twilio WhatsApp API:
            /*
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->whatsappApiKey . ':' . ''),
            ])->post("{$this->whatsappApiUrl}/Messages", [
                'From' => "whatsapp:{$this->whatsappSenderNumber}",
                'To' => "whatsapp:{$phoneNumber}",
                'Body' => $message,
            ]);
            */

            // Contoh untuk WhatsApp Business API:
            /*
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->whatsappApiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->whatsappApiUrl}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $phoneNumber,
                'text' => [
                    'body' => $message,
                ],
            ]);
            */

            // Untuk saat ini, kita hanya akan log pesan yang akan dikirim
            Log::info("WhatsApp message to {$phoneNumber}: {$message}");
            
            // Simulasikan pengiriman berhasil
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp message: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim pesan kustom
     */
    public function sendCustomMessage($phoneNumber, $message)
    {
        return $this->sendWhatsAppMessage($phoneNumber, $message);
    }
}