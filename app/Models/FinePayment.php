<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FinePayment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'fine_id',
        'amount',
        'payment_date',
        'payment_method',
        'receipt_number',
        'recorded_by',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function fine(): BelongsTo
    {
        return $this->belongsTo(Fine::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['amount', 'payment_method', 'receipt_number'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getFormattedAmountAttribute(): string
    {
        return '$'.number_format($this->amount, 2);
    }

    public function scopeByPaymentMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    public static function getPaymentMethods(): array
    {
        return [
            'cash' => 'Cash',
            'check' => 'Check',
            'card' => 'Card',
            'transfer' => 'Bank Transfer',
            'other' => 'Other',
        ];
    }
}
