<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer(['layouts.admin', 'admin.*'], function ($view) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('bookings')) {
                    $recentNotifications = \App\Models\Booking::with(['barber', 'items.service'])
                        ->latest()
                        ->take(6)
                        ->get();
                    $newBookingsCount = \App\Models\Booking::whereIn('status', ['pending', 'confirmed'])
                        ->where('created_at', '>=', now()->subHours(24))
                        ->count();
                    $view->with('adminRecentBookings', $recentNotifications);
                    $view->with('adminNewCount', $newBookingsCount);
                }
            } catch (\Throwable $e) {
                $view->with('adminRecentBookings', collect());
                $view->with('adminNewCount', 0);
            }
        });
    }
}
