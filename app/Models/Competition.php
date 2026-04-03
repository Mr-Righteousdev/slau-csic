<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'start_date',
        'end_date',
        'location',
        'website_url',
        'is_team_based',
        'max_team_size',
        'participation_status',
        'club_ranking',
        'achievements',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_team_based' => 'boolean',
        ];
    }

    public function participants()
    {
        return $this->hasMany(CompetitionParticipants::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'competition_participants')
            ->withPivot(['team_name', 'role'])
            ->withTimestamps();
    }
}
