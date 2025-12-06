<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',      // ← Add this (matches DB column)
        'model',       // ← Add this (matches DB column)
        'model_id',    // ← Add this (matches DB column)
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        // Remove 'activity_type' since it doesn't exist in DB
        // Remove 'created_at' and 'updated_at' - they're automatically handled by Eloquent
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
