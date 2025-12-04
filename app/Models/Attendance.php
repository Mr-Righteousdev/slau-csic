<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'meeting_id',
        'user_id',
        'checked_in_at',
        'check_in_method',
        'location',
        'device_info',
        'ip_address',
        'marked_by',
        'notes',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    public function isManualEntry(): bool
    {
        return $this->check_in_method === 'manual';
    }

    public function isQrCodeEntry(): bool
    {
        return $this->check_in_method === 'qr_code';
    }

    public function getCheckInMethodDisplayAttribute(): string
    {
        return match ($this->check_in_method) {
            'qr_code' => 'QR Code Scan',
            'manual' => 'Manual Entry',
            'nfc' => 'NFC Tap',
            'admin_override' => 'Admin Override',
            default => ucfirst($this->check_in_method),
        };
    }
}
