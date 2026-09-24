<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $allBookings = $user->bookings()
            ->with(['items.service', 'barber'])
            ->latest('booking_date')
            ->get();

        $today = today()->format('Y-m-d');

        $upcomingBookings = $allBookings->filter(function ($b) use ($today) {
            return $b->booking_date->format('Y-m-d') >= $today && !in_array($b->status, ['completed', 'cancelled']);
        });

        $pastBookings = $allBookings->reject(function ($b) use ($upcomingBookings) {
            return $upcomingBookings->contains('id', $b->id);
        });

        return view('user.bookings.index', compact('upcomingBookings', 'pastBookings'));
    }

    public function cancel($id)
    {
        $booking = Auth::user()->bookings()->findOrFail($id);

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Tidak dapat membatalkan reservasi yang sudah selesai atau telah dibatalkan.');
        }

        $booking->update([
            'status' => 'cancelled',
            'admin_notes' => 'Dibatalkan oleh pelanggan pada ' . now()->format('d M Y H:i'),
        ]);

        return back()->with('success', "Reservasi #{$booking->booking_number} berhasil dibatalkan.");
    }
}
