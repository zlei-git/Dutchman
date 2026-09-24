<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = today();

        $todayBookings = Booking::with(['barber', 'items.service', 'user'])
            ->whereDate('booking_date', $today)
            ->orderBy('booking_time')
            ->get();

        $todayTotal = $todayBookings->count();
        $todayConfirmed = $todayBookings->where('status', 'confirmed')->count();
        $todayPending = $todayBookings->where('status', 'pending')->count();
        $todayCompleted = $todayBookings->where('status', 'completed')->count();
        $todayRevenue = $todayBookings->whereIn('status', ['confirmed', 'completed'])->sum('total_price');

        $allBookingsCount = Booking::count();
        $activeBarbersCount = Barber::active()->count();
        $activeServicesCount = Service::active()->count();
        $customersCount = User::where('role', 'user')->count();

        $recentBookings = Booking::with(['barber', 'items.service', 'user'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'todayBookings',
            'todayTotal',
            'todayConfirmed',
            'todayPending',
            'todayCompleted',
            'todayRevenue',
            'allBookingsCount',
            'activeBarbersCount',
            'activeServicesCount',
            'customersCount',
            'recentBookings'
        ));
    }
}
