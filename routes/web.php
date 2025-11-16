<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingStatusController;
use App\Http\Controllers\RoleTestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [UserController::class, 'showLandingPage'])->name('landing');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User routes (protected) - only authenticated users can access
Route::middleware(['isUser'])->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/booking', [UserController::class, 'showBookingFlow'])->name('booking');
        Route::get('/booking/service', [UserController::class, 'showServiceSelection'])->name('service.selection');
        Route::get('/booking/barber', [UserController::class, 'showBarberSelection'])->name('barber.selection');
        Route::get('/booking/schedule', [UserController::class, 'showScheduleSelection'])->name('schedule.selection');
        Route::get('/booking/confirmation', [UserController::class, 'showConfirmation'])->name('confirmation');
        Route::get('/booking/detail', [UserController::class, 'showBookingDetail'])->name('booking.detail');
        Route::get('/products', [UserController::class, 'showProducts'])->name('products');
        Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
        Route::get('/booking/history', [UserController::class, 'getBookingHistory'])->name('booking.history');
        Route::get('/profile/data', [UserController::class, 'getProfileData'])->name('profile.data');
        Route::get('/booking/time-slots', [UserController::class, 'getAvailableTimeSlots'])->name('booking.time-slots');
        Route::post('/booking/create', [UserController::class, 'createBooking'])->name('booking.create');
	Route::post('/booking/{id}/cancel', [BookingStatusController::class, 'userCancel'])->name('booking.cancel');
    });
});

// Admin routes (protected) - only authenticated admin users can access
Route::middleware(['isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    // Main dashboard and sub-sections
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/data', [AdminController::class, 'getDashboardData'])->name('dashboard.data');
    
    // Admin sub-tabs
    Route::get('/checkin', [AdminController::class, 'showCheckIn'])->name('checkin');
    Route::get('/walkin', [AdminController::class, 'showWalkIn'])->name('walkin');
    Route::get('/schedule', [AdminController::class, 'showSchedule'])->name('schedule');
    Route::get('/report', [AdminController::class, 'showReport'])->name('report');
    Route::get('/reports/export/excel', [AdminController::class, 'exportReportToExcel'])->name('reports.export.excel');
    
    // Products and services
    Route::get('/products', [AdminController::class, 'showProducts'])->name('products');
    Route::get('/profile', [AdminController::class, 'showProfile'])->name('profile');
    
    // User management
    Route::get('/users', [AdminController::class, 'getAllUsers'])->name('users');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    
    // Product management
    Route::get('/products/{id}', [AdminController::class, 'getProduct'])->name('products.show');
    Route::post('/products', [AdminController::class, 'createProduct'])->name('products.create');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');
    
    // Service management
    Route::get('/services', [AdminController::class, 'getAllServices'])->name('services');
    Route::get('/services/{id}', [AdminController::class, 'getService'])->name('services.show');
    Route::post('/services', [AdminController::class, 'createService'])->name('services.create');
    Route::put('/services/{id}', [AdminController::class, 'updateService'])->name('services.update');
    Route::delete('/services/{id}', [AdminController::class, 'deleteService'])->name('services.delete');
    
    // Barber management
    Route::get('/barbers', [AdminController::class, 'getAllBarbers'])->name('barbers');
    Route::post('/barbers', [AdminController::class, 'createBarber'])->name('barbers.create');
    Route::put('/barbers/{id}', [AdminController::class, 'updateBarber'])->name('barbers.update');
    Route::delete('/barbers/{id}', [AdminController::class, 'deleteBarber'])->name('barbers.delete');
    
    // Profile and products data API
    Route::get('/profile/data', [AdminController::class, 'getProfileData'])->name('profile.data');
    Route::get('/products/data', [AdminController::class, 'getAllProductsAndServices'])->name('products.data');
    
    // Booking management
    Route::get('/bookings', [AdminController::class, 'getAllBookings'])->name('bookings');
    Route::post('/bookings', [AdminController::class, 'createBooking'])->name('bookings.create');
    Route::get('/bookings/time-slots', [AdminController::class, 'getAvailableTimeSlots'])->name('bookings.time-slots');
    Route::put('/bookings/{id}/status', [AdminController::class, 'updateBookingStatus'])->name('bookings.update.status');
    Route::post('/bookings/{id}/checkin', [AdminController::class, 'checkInBooking'])->name('bookings.checkin');
    Route::post('/bookings/{id}/complete', [AdminController::class, 'completeBooking'])->name('bookings.complete');
    // Additional booking status routes
    Route::post('/bookings/{id}/cukur', [BookingStatusController::class, 'startCukur'])->name('bookings.cukur');
    Route::post('/bookings/{id}/admin-cancel', [BookingStatusController::class, 'cancel'])->name('bookings.admin.cancel');
    Route::post('/bookings/{id}/cancel', [BookingStatusController::class, 'cancel'])->name('bookings.cancel');
});

// Test routes for middleware (for verification)
Route::get('/test/admin', [RoleTestController::class, 'adminOnly'])->middleware('isAdmin');
Route::get('/test/user', [RoleTestController::class, 'userOnly'])->middleware('isUser');

// Fallback routes for existing blade files
Route::view('/landingpage', 'landingpage')->name('landingpage');
Route::view('/login', 'login')->name('login.view');
Route::redirect('/admin/dashboard', '/admin/checkin')->name('admin.dashboard.view');
Route::view('/user/layanan', 'user.layanan')->name('user.layanan');
Route::view('/user/barber', 'user.barber')->name('user.barber');
Route::view('/user/jadwal', 'user.jadwal')->name('user.jadwal');
Route::view('/user/konfirmasi', 'user.konfirmasi')->name('user.konfirmasi');
Route::view('/user/detail', 'user.detail')->name('user.detail');
Route::view('/user/produk', 'user.produk')->name('user.produk');
Route::view('/user/profil', 'user.profil')->name('user.profil');
