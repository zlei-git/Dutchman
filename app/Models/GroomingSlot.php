<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroomingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'time_slot',
        'capacity',
        'is_available',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_available' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
