<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateEligibility extends Model
{
    protected $fillable = [
        'exam_attempt_id',
        'user_id',
        'exam_id',
        'eligible',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'eligible' => 'boolean',
        ];
    }

    public function examAttempt(): BelongsTo
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function scopeEligible($query)
    {
        return $query->where('eligible', true);
    }
}
