<?php

namespace App\Http\Controllers;

use App\Events\BookingStatusUpdated;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        // Middleware 'isUser' is applied at the route level in web.php
        // All methods in this controller require user authentication
    }
    
    public function dashboard()
    {
        return redirect()->route('user.service.selection');
    }

    public function showLandingPage()
    {
        return view('landingpage');
    }

    public function showBookingFlow()
    {
        return redirect()->route('user.service.selection');
    }

    public function showServiceSelection()
    {
        $services = Service::all();
        return view('user.layanan', compact('services'));
    }

    public function showBarberSelection()
    {
        $barbers = Barber::where('status', 'active')->get();
        return view('user.barber', compact('barbers'));
    }

    public function showScheduleSelection()
    {
        return view('user.jadwal');
    }

    public function showConfirmation()
    {
        return view('user.konfirmasi');
    }

    public function showBookingDetail(Request $request)
    {
        // Ambil ID booking dari query parameter
        $bookingId = $request->query('id');
        
        // Jika tidak ada ID booking, redirect ke halaman service
        if (!$bookingId) {
            return redirect()->route('user.service.selection');
        }
        
        // Ambil data booking dari database dengan relasi
        $booking = Booking::with(['user', 'service', 'barber'])
            ->where('booking_id', $bookingId)
            ->first();
        
        // Jika booking tidak ditemukan, redirect ke halaman service
        if (!$booking) {
            return redirect()->route('user.service.selection');
        }
        
        // Pastikan user yang mengakses adalah pemilik booking
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to booking details');
        }
        
        return view('user.detail', compact('booking'));
    }

    public function showProducts()
    {
        $products = Product::where('status', 'active')->get();
        return view('user.produk', compact('products'));
    }

    public function showProfile()
    {
        $user = Auth::user();
        
        // Get user's booking stats
        $totalBookings = $user->bookings()->count();
        $completedBookings = $user->bookings()->where('status', 'completed')->count();
        
        // Calculate loyalty progress - 5 bookings for 1 free service
        $loyaltyProgress = $completedBookings % 5; // Current progress in the cycle
        $completedCycles = floor($completedBookings / 5); // How many free services have been earned
        
        // Active coupons - number of free services user is entitled to (not yet used)
        $activeCoupons = $completedCycles;
        
        // For total spending - sum of all completed bookings
        $totalSpending = $user->bookings()->where('status', 'completed')->sum('total_price');
        
        return view('user.profil', [
            'user' => $user,
            'totalBookings' => $totalBookings,
            'completedBookings' => $completedBookings,
            'loyaltyProgress' => $loyaltyProgress,
            'activeCoupons' => $activeCoupons,
            'totalSpending' => $totalSpending,
        ]);
    }

    public function getProfileData()
    {
        $user = Auth::user();
        
        // Get user's booking stats
        $totalBookings = $user->bookings()->count();
        $completedBookings = $user->bookings()->where('status', 'completed')->count();
        
        // Calculate loyalty progress - 5 bookings for 1 free service
        $loyaltyProgress = $completedBookings % 5; // Current progress in the cycle
        $completedCycles = floor($completedBookings / 5); // How many free services have been earned
        
        // Active coupons - number of free services user is entitled to (not yet used)
        $activeCoupons = $completedCycles;
        
        // For total spending - sum of all completed bookings
        $totalSpending = $user->bookings()->where('status', 'completed')->sum('total_price');
        
        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            'totalBookings' => $totalBookings,
            'completedBookings' => $completedBookings,
            'loyaltyProgress' => $loyaltyProgress,
            'activeCoupons' => $activeCoupons,
            'totalSpending' => $totalSpending,
        ]);
    }

    public function getBookingHistory(Request $request)
    {
        $user = Auth::user();
        $type = $request->query('type', 'upcoming');
        
        $query = $user->bookings()->with(['service', 'barber']);
        
        if ($type === 'upcoming') {
            // Booking Mendatang: status 'confirmed' (konfirmasi) dan 'in_progress' (cukur)
            $query->where(function($q) {
                    $q->where('booking_date', '>', now()->toDateString())
                      ->orWhere(function($subQ) {
                          $subQ->where('booking_date', now()->toDateString())
                               ->where('booking_time', '>=', now()->toTimeString());
                      });
                })
                ->whereIn('status', ['pending', 'confirmed', 'in_progress']);
        } elseif ($type === 'history') {
            // Riwayat: status 'completed' (selesai)
            $query->whereIn('status', ['completed', 'canceled'])->get();
        }
        
        $bookings = $query->orderBy('booking_date', 'desc')
                         ->orderBy('booking_time', 'desc')
                         ->get();
        
        // Format data untuk frontend
        $formattedBookings = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'booking_id' => $booking->booking_id,
                'service' => [
                    'name' => $booking->service->name,
                    'price' => $booking->service->price,
                    'duration' => $booking->service->duration,
                ],
                'barber' => [
                    'name' => $booking->barber->name,
                ],
                'booking_date' => $booking->booking_date,
                'booking_time' => $booking->booking_time,
                'total_price' => $booking->total_price,
                'status' => $this->formatBookingStatus($booking->status),
                'status_raw' => $booking->status,
            ];
        });
        
        return response()->json($formattedBookings);
    }
    
    /**
     * Format status booking untuk ditampilkan di UI
     */
    private function formatBookingStatus($status)
    {
        $statusMap = [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
        
        return $statusMap[$status] ?? $status;
    }

    public function getAvailableTimeSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'barberId' => 'required|integer|exists:barbers,id',
            'serviceId' => 'required|integer|exists:services,id', // Add serviceId validation
        ]);

        $date = $request->date;
        $barberId = $request->barberId;
        $serviceId = $request->serviceId; // Add serviceId parameter

        // Ambil durasi dari service berdasarkan serviceId
        $service = Service::find($serviceId);
        $duration = $service ? $service->duration : 45; // Default durasi jika tidak ditemukan

        // Waktu operasional barbershop
        $openTime = '10:00';
        $closeTime = '21:45';

        // Konversi waktu operasional ke menit dari awal hari
        $openMinutes = $this->timeToMinutes($openTime);
        $closeMinutes = $this->timeToMinutes($closeTime);

        // Membuat slot waktu berdasarkan durasi layanan
        $slots = [];
        $currentMinutes = $openMinutes;

        while ($currentMinutes < $closeMinutes) {
            // Tambahkan slot ke daftar
            $slots[] = $this->minutesToTime($currentMinutes);
            $currentMinutes += $duration;
        }

        // Ambil booking yang sudah ada untuk barber dan tanggal tersebut
        $existingBookings = Booking::where('barber_id', $barberId)
            ->where('booking_date', $date)
            ->where('status', '!=', 'cancelled') // Tidak termasuk booking yang dibatalkan
            ->pluck('booking_time')
            ->toArray();

        // Hapus slot yang sudah terisi
        $availableSlots = [];
        foreach ($slots as $slot) {
            $isSlotAvailable = true;
            
            // Periksa apakah slot ini atau slot berdekatan sudah terisi
            foreach ($existingBookings as $existingTime) {
                // Konversi waktu booking ke menit
                $existingMinutes = $this->timeToMinutes($existingTime);
                $slotMinutes = $this->timeToMinutes($slot);
                
                // Jika slot beririsan dengan booking yang sudah ada
                if (($slotMinutes < $existingMinutes + $duration) && ($slotMinutes + $duration > $existingMinutes)) {
                    $isSlotAvailable = false;
                    break;
                }
            }
            
            // Jika tanggal adalah hari ini dan slot sudah lewat, maka tidak tersedia
            if ($date === date('Y-m-d')) {
                $currentTime = strtotime(date('H:i'));
                $slotTime = strtotime($slot);
                
                // Tambahkan beberapa menit sebagai margin
                if ($slotTime < ($currentTime + 15 * 60)) { // 15 menit margin
                    $isSlotAvailable = false;
                }
            }

            if ($isSlotAvailable) {
                $availableSlots[] = $slot;
            }
        }

        return response()->json([
            'availableSlots' => $availableSlots
        ]);
    }

    /**
     * Fungsi untuk membuat booking baru dari user
     */
    public function createBooking(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'barber_id' => 'required|exists:barbers,id',
            'booking_date' => 'required|date',
            'booking_time' => 'required|date_format:H:i',
            'phone' => 'required|string|max:15',
        ]);

        $user = Auth::user();
        
        // Ambil durasi layanan
        $service = Service::find($request->service_id);
        $duration = $service ? $service->duration : 45; // Default 45 menit jika tidak ditemukan

        // Cek apakah slot waktu tersedia
        if (!$this->isTimeSlotAvailable($request->barber_id, $request->booking_date, $request->booking_time, $duration)) {
            return response()->json(['error' => 'Slot waktu tidak tersedia'], 422);
        }

        // Buat booking ID unik
        $bookingId = 'BK' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        $booking = Booking::create([
            'user_id' => $user->id,
            'service_id' => $request->service_id,
            'barber_id' => $request->barber_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'total_price' => $service->price,
            'duration' => $duration,
            'status' => 'pending', // Default status pending
            'payment_method' => 'Walk-in', // Default, bisa diupdate nanti
            'payment_status' => 'unpaid', // Default unpaid
            'booking_id' => $bookingId,
            'phone' => $request->phone, // Simpan nomor HP untuk WhatsApp notification
        ]);

        // Broadcast the status update
        event(new BookingStatusUpdated($booking));

        return response()->json([
            'success' => true,
            'booking' => $booking,
            'booking_id' => $bookingId
        ]);
    }

    /**
     * Cek apakah slot waktu tersedia untuk barber tertentu
     */
    private function isTimeSlotAvailable($barberId, $date, $time, $duration)
    {
        // Konversi waktu booking ke menit dari awal hari
        $timeParts = explode(':', $time);
        $bookingStartMinutes = intval($timeParts[0]) * 60 + intval($timeParts[1]);
        $bookingEndMinutes = $bookingStartMinutes + $duration;

        // Ambil semua booking yang aktif (tidak dibatalkan) untuk barber dan tanggal tersebut
        $existingBookings = Booking::where('barber_id', $barberId)
            ->where('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->get();

        // Cek apakah ada bentrok waktu
        foreach ($existingBookings as $existingBooking) {
            $existingTimeParts = explode(':', $existingBooking->booking_time);
            $existingStartMinutes = intval($existingTimeParts[0]) * 60 + intval($existingTimeParts[1]);
            $existingEndMinutes = $existingStartMinutes + ($existingBooking->duration ?? 45);

            // Cek apakah waktu booking baru beririsan dengan booking yang sudah ada
            if (($bookingStartMinutes < $existingEndMinutes) && ($bookingEndMinutes > $existingStartMinutes)) {
                return false; // Ada bentrok
            }
        }

        return true; // Slot tersedia
    }

    private function timeToMinutes($time)
    {
        $parts = explode(':', $time);
        return intval($parts[0]) * 60 + intval($parts[1]);
    }

    private function minutesToTime($minutes)
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $mins);
    }
}
