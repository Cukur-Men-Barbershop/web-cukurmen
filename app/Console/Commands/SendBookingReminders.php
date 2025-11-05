<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\WhatsAppNotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendBookingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send booking reminders 10 minutes before scheduled time';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppNotificationService $whatsappService)
    {
        $this->info('Sending booking reminders...');
        
        // Hitung waktu 10 menit dari sekarang
        $reminderTime = Carbon::now()->addMinutes(10);
        
        // Format tanggal dan waktu untuk pencarian
        $date = $reminderTime->format('Y-m-d');
        $time = $reminderTime->format('H:i');
        
        // Cari booking yang harus dikirim reminder
        $bookings = Booking::where('booking_date', $date)
            ->where('booking_time', $time)
            ->where('status', 'confirmed')
            ->with(['user', 'service', 'barber'])
            ->get();
            
        $this->info("Found {$bookings->count()} bookings for reminder.");
        
        foreach ($bookings as $booking) {
            // Pastikan nomor HP tersedia
            if ($booking->phone) {
                $result = $whatsappService->sendBookingReminder($booking);
                
                if ($result) {
                    $this->info("Reminder sent to {$booking->phone} for booking {$booking->booking_id}");
                } else {
                    $this->error("Failed to send reminder to {$booking->phone} for booking {$booking->booking_id}");
                }
            } else {
                $this->warn("No phone number for booking {$booking->booking_id}");
            }
        }
        
        $this->info('Booking reminders completed.');
    }
}