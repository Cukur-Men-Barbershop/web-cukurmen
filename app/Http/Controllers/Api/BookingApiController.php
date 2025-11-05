<?php

namespace App\Http\Controllers\Api;

use App\Events\BookingStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingApiController extends Controller
{
    public function __construct()
    {
        // Middleware 'isUser' is applied at the route level in api.php
        // All methods in this controller require user authentication
    }
    
    public function getAvailableTimeSlots(Request $request)
    {
        $date = $request->query('date');
        $barberId = $request->query('barberId');
        
        if (!$date || !$barberId) {
            return response()->json(['error' => 'Date and barber ID are required'], 400);
        }
        
        // Define available time slots
        $availableSlots = [
            '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', 
            '15:00', '16:00', '17:00', '18:00', '19:00'
        ];
        
        // Get existing bookings for this barber on this date
        $bookedSlots = Booking::where('booking_date', $date)
                             ->where('barber_id', $barberId)
                             ->pluck('booking_time')
                             ->toArray();
        // For walk-in bookings
        $walkInSlots = Booking::where('booking_date', $date)
                             ->where('barber_id', $barberId)
                             ->where('payment_method', 'Walk-in')
                             ->pluck('booking_time')
                             ->toArray();
        
        $allBookedSlots = array_merge($bookedSlots, $walkInSlots);
        
        // Filter out booked slots
        $availableTimeSlots = array_values(array_diff($availableSlots, $allBookedSlots));
        
        return response()->json([
            'date' => $date,
            'barberId' => $barberId,
            'availableSlots' => $availableTimeSlots
        ]);
    }

    public function createBooking(Request $request)
    {
        $request->validate([
            'serviceId' => 'required|exists:services,id',
            'barberId' => 'required|exists:barbers,id',
            'date' => 'required|date',
            'time' => 'required',
            'totalPrice' => 'required|integer|min:0',
            'duration' => 'required|integer|min:0',
            'paymentMethod' => 'required|string'
        ]);
        
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Generate unique booking ID
        $bookingId = 'BK' . date('Ymd') . strtoupper(Str::random(6));
        
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'service_id' => $request->serviceId,
            'barber_id' => $request->barberId,
            'booking_date' => $request->date,
            'booking_time' => $request->time,
            'total_price' => $request->totalPrice,
            'duration' => $request->duration,
            'status' => 'confirmed', // Set to confirmed immediately after creation
            'payment_method' => $request->paymentMethod,
            'booking_id' => $bookingId,
        ]);
        
        // Return booking details with QR code URL (in a real app, you'd generate an actual QR code)
        return response()->json([
            'bookingId' => $booking->booking_id,
            'qrCodeUrl' => null, // In a real implementation, this would be the URL to the generated QR code
            'booking' => $booking->load(['service', 'barber', 'user'])
        ]);
    }

    public function getUserBookings()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $bookings = Auth::user()->bookings()
                                ->with(['service', 'barber'])
                                ->orderBy('booking_date', 'desc')
                                ->orderBy('booking_time', 'desc')
                                ->get();
        
        return response()->json($bookings);
    }

    public function cancelBooking($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $booking = Auth::user()->bookings()->findOrFail($id);
        
        if ($booking->status === 'completed' || $booking->status === 'cancelled') {
            return response()->json(['error' => 'Cannot cancel this booking'], 400);
        }
        
        $booking->update(['status' => 'cancelled']);
        
        // Broadcast the status update
        event(new BookingStatusUpdated($booking));
        
        return response()->json(['message' => 'Booking cancelled successfully']);
    }

    public function getAllServices()
    {
        $services = Service::all();
        return response()->json($services);
    }

    public function getAllBarbers()
    {
        $barbers = Barber::where('status', 'active')->get();
        return response()->json($barbers);
    }
}
