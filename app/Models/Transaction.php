<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaction extends Model
{
    use LogsActivity;

    protected $fillable = [
        'type',
        'category',
        'amount',
        'date',
        'description',
        'receipt_path',
        'paid_to_from',
        'payment_method',
        'status',
        'requires_approval',
        'approved_by',
        'approved_at',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'requires_approval' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['type', 'category', 'amount', 'status', 'approved_by'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getFormattedAmountAttribute(): string
    {
        return '$'.number_format($this->amount, 2);
    }

    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
