<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\WhatsAppNotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendBookingReceipts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:send-receipts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send booking receipts after completion';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppNotificationService $whatsappService)
    {
        $this->info('Sending booking receipts...');
        
        // Cari booking yang baru saja selesai (dalam 1 jam terakhir)
        $oneHourAgo = Carbon::now()->subHour();
        
        $bookings = Booking::where('status', 'completed')
            ->where('updated_at', '>=', $oneHourAgo)
            ->with(['user', 'service', 'barber'])
            ->get();
            
        $this->info("Found {$bookings->count()} recently completed bookings.");
        
        foreach ($bookings as $booking) {
            // Pastikan nomor HP tersedia
            if ($booking->phone) {
                $result = $whatsappService->sendBookingReceipt($booking);
                
                if ($result) {
                    $this->info("Receipt sent to {$booking->phone} for booking {$booking->booking_id}");
                } else {
                    $this->error("Failed to send receipt to {$booking->phone} for booking {$booking->booking_id}");
                }
            } else {
                $this->warn("No phone number for booking {$booking->booking_id}");
            }
        }
        
        $this->info('Booking receipts completed.');
    }
}