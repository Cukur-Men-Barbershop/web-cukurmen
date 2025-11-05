<?php

use App\Http\Controllers\Api\BookingApiController;
use Illuminate\Support\Facades\Route;

// Booking API routes - need user authentication
Route::middleware(['isUser'])->group(function () {
    Route::get('/booking/time-slots', [BookingApiController::class, 'getAvailableTimeSlots']);
    Route::post('/booking', [BookingApiController::class, 'createBooking']);
    Route::get('/bookings', [BookingApiController::class, 'getUserBookings']);
    Route::post('/bookings/{id}/cancel', [BookingApiController::class, 'cancelBooking']);
    Route::get('/user/profile', function () {
        $user = auth()->user();
        
        // Get user's booking stats
        $totalBookings = $user->bookings()->count();
        $completedBookings = $user->bookings()->where('status', 'completed')->count();
        
        // For loyalty progress - assuming 5 bookings to reach next tier
        $loyaltyProgress = min(5, $completedBookings);
        
        // For active coupons - assuming there are none in this basic implementation
        $activeCoupons = 0;
        
        // For total spending - sum of all completed bookings
        $totalSpending = $user->bookings()->where('status', 'completed')->sum('total_price');
        
        return response()->json([
            'user' => $user,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'totalBookings' => $totalBookings,
            'completedBookings' => $completedBookings,
            'loyaltyProgress' => $loyaltyProgress,
            'activeCoupons' => $activeCoupons,
            'totalSpending' => $totalSpending,
        ]);
    });
});

// Public API routes (no authentication required)
Route::get('/services', [BookingApiController::class, 'getAllServices']);
Route::get('/barbers', [BookingApiController::class, 'getAllBarbers']);