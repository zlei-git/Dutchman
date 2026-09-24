<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use App\Services\BookingScheduleService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    protected BookingScheduleService $scheduleService;

    public function __construct(BookingScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function create(Request $request)
    {
        $services = Service::active()->get();
        $barbers = Barber::active()->get();
        $addons = Addon::active()->get();

        $selectedServiceId = $request->query('service_id');
        $selectedBarberId = $request->query('barber_id');
        $selectedDate = $request->query('date', today()->format('Y-m-d'));
        $selectedTime = $request->query('time');

        // Generate next 14 days for the horizontal date selector
        $dates = [];
        for ($i = 0; $i < 14; $i++) {
            $d = today()->addDays($i);
            $dates[] = [
                'date' => $d->format('Y-m-d'),
                'day_name' => $d->translatedFormat('D'),
                'day_num' => $d->format('d'),
                'month_name' => $d->translatedFormat('M'),
                'is_today' => $d->isToday(),
            ];
        }

        $initialSlots = $this->scheduleService->getSlots($selectedDate, $selectedBarberId ? (int)$selectedBarberId : null);
        $initialChairs = $this->scheduleService->getChairsStatus($selectedDate, $selectedTime ?: '11:00');

        $user = Auth::user();

        return view('booking.create', compact(
            'services',
            'barbers',
            'addons',
            'dates',
            'selectedServiceId',
            'selectedBarberId',
            'selectedDate',
            'selectedTime',
            'initialSlots',
            'initialChairs',
            'user'
        ));
    }

    public function getChairs(Request $request)
    {
        $date = $request->query('date', today()->format('Y-m-d'));
        $time = $request->query('time', '11:00');

        $chairs = $this->scheduleService->getChairsStatus($date, $time);

        return response()->json([
            'success' => true,
            'date' => $date,
            'time' => $time,
            'chairs' => $chairs,
        ]);
    }

    public function getSlots(Request $request)
    {
        $date = $request->query('date', today()->format('Y-m-d'));
        $barberId = $request->query('barber_id') ? (int) $request->query('barber_id') : null;

        $slots = $this->scheduleService->getSlots($date, $barberId);

        return response()->json([
            'success' => true,
            'date' => $date,
            'barber_id' => $barberId,
            'slots' => $slots,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'exists:addons,id',
            'barber_id' => 'nullable|exists:barbers,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|string',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:25',
            'customer_email' => 'nullable|email|max:100',
            'notes' => 'nullable|string|max:500',
            'promo_code' => 'nullable|string|max:50',
        ]);

        if (empty($validated['service_id']) && empty($validated['service_ids'])) {
            return back()->withInput()->with('error', 'Silakan pilih layanan potong rambut terlebih dahulu.');
        }

        try {
            $user = Auth::user();
            $booking = $this->scheduleService->createBooking($validated, $user);

            // Flow: Redirect directly to Appointment Booking Summary before payment
            return redirect()->route('booking.summary.before', $booking->id);
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * 1. Booking Summary Before Payment (Clean invoice with [ PAY NOW ] button)
     */
    public function summaryBeforePayment(Booking $booking)
    {
        $booking->loadMissing(['items.service', 'addons.addon', 'barber', 'payment']);

        return view('booking.summary-before-payment', compact('booking'));
    }

    /**
     * 7. Booked Successfully (Post-Payment Success Screen with subtle checkmark animation)
     */
    public function success(Booking $booking)
    {
        $booking->loadMissing(['items.service', 'addons.addon', 'barber', 'payment']);

        return view('booking.success', compact('booking'));
    }

    /**
     * 8. Booking Summary After Payment (Official Confirmed Summary with Add to Calendar & Contact)
     */
    public function summary(Booking $booking)
    {
        $booking->loadMissing(['items.service', 'addons.addon', 'barber', 'payment']);

        return view('booking.summary', compact('booking'));
    }

    /**
     * Live Polling API for customer waiting screen
     */
    public function checkStatus(Booking $booking)
    {
        $statusLabels = [
            'pending' => 'Menunggu Persetujuan Admin',
            'confirmed' => 'Terkonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return response()->json([
            'success' => true,
            'id' => $booking->id,
            'booking_number' => $booking->booking_number,
            'status' => $booking->status,
            'status_label' => $statusLabels[$booking->status] ?? ucfirst($booking->status),
            'is_confirmed' => $booking->status === 'confirmed',
            'is_cancelled' => $booking->status === 'cancelled',
            'success_url' => route('booking.success', $booking->id),
            'summary_url' => route('booking.summary', $booking->id),
        ]);
    }

    /**
     * 9. Pending Payment
     */
    public function pending(Booking $booking)
    {
        $booking->loadMissing(['items.service', 'addons.addon', 'barber', 'payment']);

        return view('booking.pending', compact('booking'));
    }

    /**
     * 10. Failed Payment
     */
    public function failed(Booking $booking)
    {
        $booking->loadMissing(['items.service', 'addons.addon', 'barber', 'payment']);

        return view('booking.failed', compact('booking'));
    }

    /**
     * 10. Expired Payment
     */
    public function expired(Booking $booking)
    {
        $booking->loadMissing(['items.service', 'addons.addon', 'barber', 'payment']);

        return view('booking.expired', compact('booking'));
    }

    /**
     * 11. Payment Cancelled
     */
    public function cancelled(Booking $booking)
    {
        $booking->loadMissing(['items.service', 'addons.addon', 'barber', 'payment']);

        return view('booking.cancelled', compact('booking'));
    }

    /**
     * Legacy confirmation route support
     */
    public function confirmation($bookingNumber)
    {
        $booking = Booking::with(['items.service', 'addons.addon', 'barber', 'user', 'payment'])
            ->where('booking_number', $bookingNumber)
            ->firstOrFail();

        return view('booking.summary', compact('booking'));
    }
}
