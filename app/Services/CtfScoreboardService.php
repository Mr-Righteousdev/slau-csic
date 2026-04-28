<?php

namespace App\Services;

use App\Models\CtfCompetition;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CtfScoreboardService
{
    protected int $cacheTtl = 60; // 1 minute for CTF (faster than general leaderboard's 5 min)

    public function getScoreboard(CtfCompetition $competition, int $limit = 100): Collection
    {
        $cacheKey = "ctf.scoreboard.{$competition->id}.{$limit}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($competition, $limit) {
            return $this->computeScoreboard($competition, $limit);
        });
    }

    public function getUserRank(CtfCompetition $competition, User $user): ?int
    {
        $scoreboard = $this->getScoreboard($competition);
        $entry = $scoreboard->firstWhere('user_id', $user->id);
        return $entry ? $entry['rank'] : null;
    }

    public function invalidateCache(CtfCompetition $competition): void
    {
        Cache::forget("ctf.scoreboard.{$competition->id}.100");
        Cache::forget("ctf.scoreboard.{$competition->id}.10");
    }

    protected function computeScoreboard(CtfCompetition $competition, int $limit): Collection
    {
        $challengeIds = $competition->challenges()->pluck('id');

        $users = User::query()
            ->whereHas('ctfSubmissions', function ($query) use ($challengeIds) {
                $query->whereIn('ctf_challenge_id', $challengeIds)
                    ->where('is_correct', true);
            })
            ->withSum(['ctfSubmissions' => function ($query) use ($challengeIds) {
                $query->whereIn('ctf_challenge_id', $challengeIds)
                    ->where('is_correct', true);
            }], 'points_awarded')
            ->orderBy('ctf_submissions_sum_points_awarded', 'desc')
            ->limit($limit)
            ->get()
            ->map(function (User $user, $index) {
                return [
                    'rank' => $index + 1,
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'student_id' => $user->student_id,
                    'avatar_url' => $user->avatar_url,
                    'total_score' => (int) ($user->ctf_submissions_sum_points_awarded ?? 0),
                    'solves_count' => $user->ctfSubmissions()->whereIn('ctf_challenge_id', $challengeIds ?? [])->where('is_correct', true)->count(),
                ];
            });

        return $users;
    }
}