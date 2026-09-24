<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\GroomingService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Promotion;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index()
    {
        // Monthly sales summary
        $monthlySales = Order::where('payment_status', 'PAID')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();

        // Daily sales (last 7 days)
        $dailySales = Order::where('payment_status', 'PAID')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // Orders by status
        $ordersByStatus = Order::select('order_status', DB::raw('COUNT(*) as count'))
            ->groupBy('order_status')
            ->get();

        // Delivery vs Pickup
        $ordersByType = Order::select('order_type', DB::raw('COUNT(*) as count'))
            ->groupBy('order_type')
            ->get();

        // Bookings by status
        $bookingsByStatus = Booking::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Bookings by branch
        $bookingsByBranch = Booking::select('branch_id', DB::raw('COUNT(*) as count'))
            ->with('branch')
            ->groupBy('branch_id')
            ->get();

        // Popular Grooming Services
        $popularServices = Booking::select('grooming_service_id', DB::raw('COUNT(*) as total_bookings'))
            ->with('groomingService')
            ->groupBy('grooming_service_id')
            ->orderByDesc('total_bookings')
            ->take(5)
            ->get();

        // Best performing pet products
        $topSelling = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // Promo usage & total discount
        $promoStats = Order::whereNotNull('promo_code')
            ->where('promo_code', '!=', '')
            ->select('promo_code', DB::raw('COUNT(*) as times_used'), DB::raw('SUM(discount) as total_discount_given'))
            ->groupBy('promo_code')
            ->get();

        $totalDiscountGiven = Order::sum('discount');

        return view('admin.reports.index', compact(
            'monthlySales',
            'dailySales',
            'ordersByStatus',
            'ordersByType',
            'bookingsByStatus',
            'bookingsByBranch',
            'popularServices',
            'topSelling',
            'promoStats',
            'totalDiscountGiven'
        ));
    }
}
