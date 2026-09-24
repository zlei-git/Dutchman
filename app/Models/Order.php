<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'branch_id',
        'order_type',
        'subtotal',
        'discount',
        'shipping_fee',
        'total',
        'promo_code',
        'shipping_method',
        'payment_method',
        'payment_status',
        'order_status',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'notes',
        'snap_token',
        'paid_at',
        'shipped_at',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function gatewayTransactions()
    {
        return $this->hasMany(PaymentGatewayTransaction::class);
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;
        return sprintf('PWM-%s-%04d', $date, $count);
    }
}
