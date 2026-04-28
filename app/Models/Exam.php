<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'duration_minutes',
        'passing_score',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'passing_score' => 'integer',
            'status' => 'string',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class, 'exam_id')->orderBy('order');
    }

    public function examQuestions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class, 'exam_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function toExamShieldFormat(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'duration_minutes' => $this->duration_minutes,
            'passing_score' => $this->passing_score,
            'status' => $this->status,
            'questions' => $this->questions->map(fn ($eq) => [
                'id' => $eq->id,
                'order' => $eq->order,
                'marks' => $eq->effective_marks,
                'question' => $eq->question?->toExamShieldFormat(),
            ])->toArray(),
        ];
    }
}
