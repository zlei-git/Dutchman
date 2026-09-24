<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'brand',
        'pet_type',
        'ingredients',
        'weight_info',
        'shipping_info',
        'badge',
        'rating',
        'review_count',
        'is_featured',
        'is_new',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'rating' => 'decimal:1',
        'review_count' => 'integer',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Helper: effective active price
    public function getEffectivePriceAttribute()
    {
        return ($this->discount_price && $this->discount_price > 0 && $this->discount_price < $this->price)
            ? $this->discount_price
            : $this->price;
    }

    // Helper: total available stock
    public function getTotalStockAttribute()
    {
        return $this->variants->sum('stock');
    }

    // Primary image url or placeholder
    public function getImageUrlAttribute()
    {
        $primary = $this->images->where('is_primary', true)->first() ?? $this->images->first();
        if ($primary && $primary->image_path) {
            if (str_starts_with($primary->image_path, 'http')) {
                return $primary->image_path;
            }
            return asset($primary->image_path);
        }
        return asset('images/products/placeholder.svg');
    }
}
