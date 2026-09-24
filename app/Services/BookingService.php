<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\GroomingService;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function getTimeSlots(Branch $branch, string $date): array
    {
        $slots = [];
        $open = Carbon::createFromTimeString($branch->opening_time ?? '09:00:00');
        $close = Carbon::createFromTimeString($branch->closing_time ?? '20:00:00');

        $current = $open->copy();
        while ($current->lt($close)) {
            $slotLabel = $current->format('H:i');

            // Count existing bookings for this slot on this date
            $bookedCount = Booking::where('branch_id', $branch->id)
                ->where('booking_date', $date)
                ->where('booking_time', $slotLabel)
                ->whereNotIn('status', ['CANCELLED', 'NO_SHOW'])
                ->count();

            $maxCapacity = $branch->slot_capacity ?? 2;
            $availableCapacity = max(0, $maxCapacity - $bookedCount);

            $slots[] = [
                'time' => $slotLabel,
                'available' => $availableCapacity > 0,
                'remaining' => $availableCapacity,
                'total' => $maxCapacity,
            ];

            $current->addHour();
        }

        return $slots;
    }

    public function createBooking(User $user, array $data): Booking
    {
        return DB::transaction(function () use ($user, $data) {
            $branch = Branch::findOrFail($data['branch_id']);
            $service = GroomingService::findOrFail($data['grooming_service_id']);

            $bookingDate = Carbon::parse($data['booking_date'])->format('Y-m-d');
            $bookingTime = $data['booking_time'];

            // Prevent past booking dates
            if (Carbon::parse($bookingDate)->isPast() && !Carbon::parse($bookingDate)->isToday()) {
                throw new Exception('Tanggal booking tidak boleh di masa lalu.');
            }

            // Atomic slot collision check with lock to guarantee zero double bookings
            $maxCapacity = $branch->slot_capacity ?? 2;
            $bookedCount = Booking::where('branch_id', $branch->id)
                ->where('booking_date', $bookingDate)
                ->where('booking_time', $bookingTime)
                ->whereNotIn('status', ['CANCELLED', 'NO_SHOW'])
                ->lockForUpdate()
                ->count();

            if ($bookedCount >= $maxCapacity) {
                throw new Exception("Slot grooming pukul {$bookingTime} sudah penuh. Silakan pilih jam lain.");
            }

            $bookingNumber = Booking::generateBookingNumber();

            return Booking::create([
                'booking_number' => $bookingNumber,
                'user_id' => $user->id,
                'branch_id' => $branch->id,
                'grooming_service_id' => $service->id,
                'pet_name' => $data['pet_name'],
                'pet_type' => $data['pet_type'] ?? 'Cat',
                'pet_breed' => $data['pet_breed'] ?? null,
                'booking_date' => $bookingDate,
                'booking_time' => $bookingTime,
                'customer_name' => $data['customer_name'],
                'phone' => $data['phone'],
                'notes' => $data['notes'] ?? null,
                'status' => 'CONFIRMED', // Instant confirmation for good customer experience
            ]);
        });
    }
}
