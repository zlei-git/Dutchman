<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'user_id',
        'barber_id',
        'chair_code',
        'booking_date',
        'booking_time',
        'duration_minutes',
        'total_price',
        'status',
        'customer_name',
        'customer_phone',
        'customer_email',
        'notes',
        'admin_notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_price' => 'decimal:2',
        'duration_minutes' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }

    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }

    public function addons()
    {
        return $this->hasMany(BookingAddon::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function isPaid(): bool
    {
        return $this->payment && $this->payment->status === 'paid';
    }

    public function getChairDisplayNameAttribute(): string
    {
        $code = $this->chair_code ?: ($this->barber?->chair_code ?: 'A1');
        return "Meja " . strtoupper($code);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getServiceAttribute()
    {
        return $this->items->first()?->service;
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->booking_date ? $this->booking_date->translatedFormat('l, d F Y') : '';
    }

    public static function generateBookingNumber(): string
    {
        $date = now()->format('ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return "DTC-{$date}-{$random}";
    }
}
