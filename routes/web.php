<?php

use App\Http\Controllers\Admin\AdminBarberController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserBookingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes — Dutchman Barbershop
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/map', [HomeController::class, 'map'])->name('map');

// Services
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

// Barbers
Route::get('/barbers', [BarberController::class, 'index'])->name('barbers.index');

// Booking Engine
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// Payment Gateway Flow — Midtrans Snap & Appointment Lifecycle
Route::get('/booking/{booking}/summary-before-payment', [BookingController::class, 'summaryBeforePayment'])->name('booking.summary.before');
Route::post('/booking/{booking}/pay', [\App\Http\Controllers\PaymentController::class, 'createSnap'])->name('booking.pay');
Route::post('/payment/midtrans/token/{booking}', [\App\Http\Controllers\PaymentController::class, 'createSnap'])->name('payment.midtrans.token');
Route::post('/booking/{booking}/simulate-success', [\App\Http\Controllers\PaymentController::class, 'simulateSuccess'])->name('booking.simulate.success');
Route::get('/booking/{booking}/success', [BookingController::class, 'success'])->name('booking.success');
Route::get('/booking/{booking}/pending', [BookingController::class, 'pending'])->name('booking.pending');
Route::get('/booking/{booking}/summary', [BookingController::class, 'summary'])->name('booking.summary');
Route::get('/booking/{booking}/failed', [BookingController::class, 'failed'])->name('booking.failed');
Route::get('/booking/{booking}/expired', [BookingController::class, 'expired'])->name('booking.expired');
Route::get('/booking/{booking}/cancelled', [BookingController::class, 'cancelled'])->name('booking.cancelled');

// Midtrans Webhook Notification Endpoint (CSRF exempt)
Route::post('/payment/midtrans/notification', [\App\Http\Controllers\PaymentController::class, 'notification'])->name('payment.midtrans.notification');
Route::post('/api/payment/midtrans/notification', [\App\Http\Controllers\PaymentController::class, 'notification'])->name('midtrans.notification');

Route::get('/booking/confirmation/{bookingNumber}', [BookingController::class, 'confirmation'])->name('booking.confirmation');

// Dynamic Schedule & Chairs API
Route::get('/api/available-slots', [BookingController::class, 'getSlots'])->name('api.slots');
Route::get('/api/slots', [BookingController::class, 'getSlots'])->name('api.slots.alias');
Route::get('/api/chairs', [BookingController::class, 'getChairs'])->name('api.chairs');

// Live Booking Status Check (Customer Waiting Screen polling)
Route::get('/booking/{booking}/status', [BookingController::class, 'checkStatus'])->name('booking.check_status');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Portal (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => redirect()->route('user.bookings.index'))->name('user.dashboard');
    Route::get('/my-bookings', [UserBookingController::class, 'index'])->name('user.bookings.index');
    Route::post('/my-bookings/{id}/cancel', [UserBookingController::class, 'cancel'])->name('user.bookings.cancel');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('user.profile.password');
});

/*
|--------------------------------------------------------------------------
| Admin Management (role:admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.alt');

    // Bookings
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::put('/bookings/{id}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
    Route::delete('/bookings/{id}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');

    // Services CRUD
    Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
    Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
    Route::put('/services/{id}', [AdminServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{id}', [AdminServiceController::class, 'destroy'])->name('services.destroy');

    // Barbers CRUD
    Route::get('/barbers', [AdminBarberController::class, 'index'])->name('barbers.index');
    Route::post('/barbers', [AdminBarberController::class, 'store'])->name('barbers.store');
    Route::put('/barbers/{id}', [AdminBarberController::class, 'update'])->name('barbers.update');
    Route::post('/barbers/{id}/toggle-maintenance', [AdminBarberController::class, 'toggleMaintenance'])->name('barbers.toggleMaintenance');
    Route::delete('/barbers/{id}', [AdminBarberController::class, 'destroy'])->name('barbers.destroy');

    // Customers
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers/{id}/toggle', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggle');

    // Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');

    // Notifications Feed API (Realtime Bell / Polling)
    Route::get('/api/notifications', function () {
        $bookings = \App\Models\Booking::with(['barber', 'items.service'])->latest()->take(8)->get()->map(function ($b) {
            return [
                'id' => $b->id,
                'booking_number' => $b->booking_number,
                'customer_name' => $b->customer_name,
                'customer_phone' => $b->customer_phone,
                'service_name' => $b->items->first()?->service_name ?? 'Layanan Barbershop',
                'barber_name' => $b->barber?->name ?? 'Barber Bebas',
                'chair_code' => $b->chair_code ?: ($b->barber?->chair_code ?: 'A1'),
                'total_formatted' => $b->formatted_total_price,
                'status' => $b->status,
                'time_ago' => $b->created_at?->diffForHumans() ?? 'Baru saja',
                'booking_date' => $b->booking_date?->format('d M Y') ?? '',
                'booking_time' => substr($b->booking_time, 0, 5),
                'url' => route('admin.bookings.index') . '?search=' . $b->booking_number,
            ];
        });
        $unreadCount = \App\Models\Booking::whereIn('status', ['pending', 'confirmed'])
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        return response()->json([
            'success' => true,
            'count' => $unreadCount,
            'notifications' => $bookings,
        ]);
    })->name('notifications');
});
