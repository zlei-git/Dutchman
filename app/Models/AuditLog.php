<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'record_id',
        'old_data',
        'new_data',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($action, $module, $record_id = null, $old_data = null, $new_data = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'module' => $module,
            'record_id' => $record_id,
            'old_data' => $old_data,
            'new_data' => $new_data,
        ]);
    }
}
