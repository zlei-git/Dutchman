<?php

namespace App\Services;

use App\Models\Addon;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\BookingAddon;
use App\Models\BookingItem;
use App\Models\Promotion;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class BookingScheduleService
{
    /**
     * Standard working hours: 10:00 to 21:00 (last appointment at 20:30)
     * Slot interval: 30 minutes
     */
    public function getWorkingHours(): array
    {
        $slots = [];
        $start = Carbon::createFromTime(10, 0, 0);
        $end = Carbon::createFromTime(20, 30, 0);

        while ($start->lte($end)) {
            $slots[] = $start->format('H:i');
            $start->addMinutes(30);
        }

        return $slots;
    }

    /**
     * Generate available time slots for a given date and optional barber
     */
    public function getSlots(string $date, ?int $barberId = null): array
    {
        $parsedDate = Carbon::parse($date)->startOfDay();
        $isPastDate = $parsedDate->lt(today());
        $isToday = $parsedDate->isToday();
        $currentTime = now()->format('H:i');

        $activeBarbers = Barber::active()->get();
        $totalBarbersCount = $activeBarbers->count();

        // Get all active bookings for this date
        $existingBookings = Booking::whereDate('booking_date', $parsedDate)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $allTimeSlots = $this->getWorkingHours();
        $results = [];

        foreach ($allTimeSlots as $timeSlot) {
            // Can't book past hours if date is today
            if ($isPastDate || ($isToday && $timeSlot <= $currentTime)) {
                $results[] = [
                    'time' => $timeSlot,
                    'available' => false,
                    'reason' => 'Past time',
                ];
                continue;
            }

            if ($barberId) {
                // Check specific barber
                $isBooked = $existingBookings->contains(function ($b) use ($barberId, $timeSlot) {
                    return $b->barber_id == $barberId && $b->booking_time === $timeSlot;
                });

                $results[] = [
                    'time' => $timeSlot,
                    'available' => !$isBooked,
                    'reason' => $isBooked ? 'Barber booked' : 'Available',
                ];
            } else {
                // "Any Available Barber"
                $bookedBarberIds = $existingBookings->where('booking_time', $timeSlot)
                    ->pluck('barber_id')
                    ->filter()
                    ->toArray();

                $availableCount = $totalBarbersCount - count(array_unique($bookedBarberIds));

                $results[] = [
                    'time' => $timeSlot,
                    'available' => $availableCount > 0,
                    'available_barbers_count' => max(0, $availableCount),
                    'reason' => $availableCount > 0 ? 'Available' : 'All barbers booked',
                ];
            }
        }

        return $results;
    }

    /**
     * Atomically create a booking with strict conflict validation
     */
    public function createBooking(array $data, ?User $user = null): Booking
    {
        return DB::transaction(function () use ($data, $user) {
            // 1. Resolve Primary & Additional Services
            $serviceIds = [];
            if (!empty($data['service_ids']) && is_array($data['service_ids'])) {
                $serviceIds = array_filter(array_map('intval', $data['service_ids']));
            } elseif (!empty($data['service_id'])) {
                $serviceIds = [(int) $data['service_id']];
            }

            if (empty($serviceIds)) {
                throw new Exception('Paling sedikit satu layanan harus dipilih.');
            }

            $services = Service::active()->whereIn('id', $serviceIds)->get();
            if ($services->isEmpty()) {
                throw new Exception('Layanan yang dipilih tidak ditemukan.');
            }

            $primaryService = $services->first();
            $totalDuration = $services->sum('duration_minutes');
            $servicesTotal = $services->sum('price');

            $date = Carbon::parse($data['booking_date'])->format('Y-m-d');
            $time = $data['booking_time'];
            $barberId = !empty($data['barber_id']) ? (int) $data['barber_id'] : null;

            // 2. Lock bookings table rows for this date and time to prevent race conditions
            $activeBookingsAtSlot = Booking::whereDate('booking_date', $date)
                ->where('booking_time', $time)
                ->whereIn('status', ['pending', 'confirmed'])
                ->lockForUpdate()
                ->get();

            // 3. Resolve Meja / Chair code (e.g. A1, A2, A3, A4)
            $activeBarbers = Barber::active()->lockForUpdate()->get();
            $assignedBarber = null;

            if ($barberId) {
                $assignedBarber = $activeBarbers->firstWhere('id', $barberId);
                if (!$assignedBarber) {
                    throw new Exception('Meja yang dipilih tidak ditemukan atau sedang tidak aktif.');
                }

                if ($assignedBarber->is_maintenance) {
                    throw new Exception("Meja {$assignedBarber->chair_code} sedang diperbaiki. Silakan pilih nomor meja lain.");
                }

                $collision = $activeBookingsAtSlot->firstWhere('barber_id', $barberId);
                if ($collision) {
                    throw new Exception("Kursi meja {$assignedBarber->chair_code} sudah dibooking pada jam {$time} WIB. Silakan pilih meja lain atau jam lain.");
                }
            } else {
                // Pilih Otomatis: cari meja yang tidak sedang maintenance dan belum terisi booking pada jam tersebut
                $busyBarberIds = $activeBookingsAtSlot->pluck('barber_id')->filter()->toArray();
                $assignedBarber = $activeBarbers->where('is_maintenance', false)->first(function ($b) use ($busyBarberIds) {
                    return !in_array($b->id, $busyBarberIds);
                });

                if (!$assignedBarber) {
                    throw new Exception("Semua kursi meja sudah terisi atau sedang diperbaiki pada tanggal {$date} jam {$time} WIB. Silakan pilih waktu lain.");
                }

                $barberId = $assignedBarber->id;
            }

            $chairCode = $assignedBarber?->chair_code ?: 'A1';

            // 4. Resolve Add-ons (Drinks & Grooming) from Database
            $addonsTotal = 0;
            $selectedAddons = [];
            $addonInputs = $data['addon_ids'] ?? ($data['addons'] ?? []);

            if (!empty($addonInputs)) {
                $addonIds = [];
                $quantities = [];

                if (is_array($addonInputs)) {
                    foreach ($addonInputs as $key => $val) {
                        if (is_numeric($key) && is_numeric($val)) {
                            if ($val > 0 && $key > 0 && !in_array($key, [0, 1, 2, 3, 4])) {
                                $addonIds[] = (int) $key;
                                $quantities[(int) $key] = (int) $val;
                            } else {
                                $addonIds[] = (int) $val;
                                $quantities[(int) $val] = 1;
                            }
                        }
                    }
                }

                if (!empty($addonIds)) {
                    $dbAddons = Addon::active()->whereIn('id', array_unique($addonIds))->get();
                    foreach ($dbAddons as $dbAddon) {
                        $qty = max(1, $quantities[$dbAddon->id] ?? 1);
                        $sub = $dbAddon->price * $qty;
                        $addonsTotal += $sub;
                        $selectedAddons[] = [
                            'addon' => $dbAddon,
                            'quantity' => $qty,
                            'price' => $dbAddon->price,
                            'name' => $dbAddon->name,
                        ];
                    }
                }
            }

            // 5. Calculate Final Price & Promo
            $totalPrice = $servicesTotal + $addonsTotal;
            if (!empty($data['promo_code'])) {
                $promo = Promotion::where('code', strtoupper($data['promo_code']))->first();
                if ($promo && $promo->isValid()) {
                    if ($promo->discount_percent > 0) {
                        $totalPrice -= ($totalPrice * ($promo->discount_percent / 100));
                    } elseif ($promo->discount_amount > 0) {
                        $totalPrice -= $promo->discount_amount;
                    }
                    $totalPrice = max(0, $totalPrice);
                }
            }

            // 6. Create Booking (Status: pending until payment is confirmed)
            $booking = Booking::create([
                'booking_number' => Booking::generateBookingNumber(),
                'user_id' => $user?->id,
                'barber_id' => $barberId,
                'chair_code' => $chairCode,
                'booking_date' => $date,
                'booking_time' => $time,
                'duration_minutes' => $totalDuration,
                'total_price' => $totalPrice,
                'status' => $data['status'] ?? 'pending',
                'customer_name' => $data['customer_name'] ?? ($user?->name ?? 'Gentleman Guest'),
                'customer_phone' => $data['customer_phone'] ?? ($user?->phone ?? '-'),
                'customer_email' => $data['customer_email'] ?? ($user?->email ?? null),
                'notes' => $data['notes'] ?? null,
            ]);

            // 7. Create Snapshot Booking Items for All Selected Services
            foreach ($services as $svc) {
                BookingItem::create([
                    'booking_id' => $booking->id,
                    'service_id' => $svc->id,
                    'price' => $svc->price,
                    'duration' => $svc->duration_minutes,
                ]);
            }

            // 8. Create Snapshot Booking Addons
            foreach ($selectedAddons as $item) {
                BookingAddon::create([
                    'booking_id' => $booking->id,
                    'addon_id' => $item['addon']->id,
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return $booking;
        });
    }

    /**
     * Get real-time status for all chairs/tables on a specific date & time
     * Returns: available, booked ("Kursi sudah dibooking"), or maintenance ("Meja sedang diperbaiki")
     */
    public function getChairsStatus(string $date, string $time): array
    {
        $parsedDate = Carbon::parse($date)->startOfDay();
        $barbers = Barber::active()->orderBy('sort_order')->orderBy('chair_code')->get();

        $activeBookingsAtSlot = Booking::whereDate('booking_date', $parsedDate)
            ->where('booking_time', $time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $results = [];
        foreach ($barbers as $barber) {
            $chairCode = $barber->chair_code ?: 'A' . $barber->id;
            $isBooked = $activeBookingsAtSlot->contains(function ($b) use ($barber, $chairCode) {
                return $b->barber_id == $barber->id || $b->chair_code === $chairCode;
            });

            if ($barber->is_maintenance) {
                $status = 'maintenance';
                $badgeText = 'Meja sedang diperbaiki';
                $warningText = 'Sedang dalam perbaikan teknis';
                $available = false;
            } elseif ($isBooked) {
                $status = 'booked';
                $badgeText = 'Kursi sudah dibooking';
                $warningText = 'Jadwal jam ini sudah terisi';
                $available = false;
            } else {
                $status = 'available';
                $badgeText = 'Tersedia';
                $warningText = '';
                $available = true;
            }

            $results[] = [
                'id' => $barber->id,
                'chair_code' => $chairCode,
                'title' => 'MEJA ' . $chairCode,
                'subtitle' => 'Station Kursi ' . substr($chairCode, 1),
                'status' => $status,
                'badge_text' => $badgeText,
                'warning_text' => $warningText,
                'available' => $available,
            ];
        }

        return $results;
    }
}
