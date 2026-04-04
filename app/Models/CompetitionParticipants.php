<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionParticipants extends Model
{
    use HasFactory;

    protected $table = 'competition_participants';

    protected $fillable = [
        'competition_id',
        'user_id',
        'team_name',
        'role',
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
