<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $activeOrdersCount = $user->orders()
            ->whereNotIn('order_status', ['COMPLETED', 'CANCELLED'])
            ->count();

        $upcomingBookingsCount = $user->bookings()
            ->whereIn('status', ['PENDING', 'CONFIRMED'])
            ->count();

        $wishlistCount = $user->wishlists()->count();

        $recentOrders = $user->orders()
            ->with(['items.product.images'])
            ->take(3)
            ->get();

        $recentBookings = $user->bookings()
            ->with(['branch', 'groomingService'])
            ->take(3)
            ->get();

        return view('user.dashboard', compact(
            'user',
            'activeOrdersCount',
            'upcomingBookingsCount',
            'wishlistCount',
            'recentOrders',
            'recentBookings'
        ));
    }
}
