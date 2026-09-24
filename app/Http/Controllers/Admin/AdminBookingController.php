<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['barber', 'items.service', 'user'])->latest('booking_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('barber_id')) {
            $query->where('barber_id', $request->barber_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(15)->withQueryString();
        $barbers = Barber::all();

        return view('admin.bookings.index', compact('bookings', 'barbers'));
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $booking->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $booking->admin_notes,
        ]);

        $statusLabels = [
            'pending' => 'MENUNGGU',
            'confirmed' => 'TERKONFIRMASI',
            'completed' => 'SELESAI',
            'cancelled' => 'DIBATALKAN',
        ];
        $displayStatus = $statusLabels[$booking->status] ?? strtoupper($booking->status);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $booking->status,
                'message' => "Booking #{$booking->booking_number} berhasil {$displayStatus}."
            ]);
        }

        return back()->with('success', "Status booking #{$booking->booking_number} berhasil diperbarui menjadi {$displayStatus}.");
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $number = $booking->booking_number;
        $booking->delete();

        return back()->with('success', "Data booking #{$number} berhasil dihapus.");
    }
}
