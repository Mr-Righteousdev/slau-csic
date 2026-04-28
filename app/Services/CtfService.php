<?php

namespace App\Services;

use App\Models\CtfChallenge;
use App\Models\CtfSubmission;
use App\Models\User;
use Illuminate\Support\Collection;

class CtfService
{
    public function __construct(
        protected GamificationService $gamificationService,
    ) {}

    public function submitFlag(
        CtfChallenge $challenge,
        User $user,
        string $submittedFlag,
        ?string $ipAddress = null
    ): array {
        // 1. Check if already solved
        if ($challenge->isSolvedBy($user)) {
            return ['success' => false, 'error' => 'already_solved', 'message' => 'You have already solved this challenge'];
        }

        // 2. Verify the flag
        if (! $challenge->verifyFlag($submittedFlag)) {
            // Log the wrong attempt
            $attempt = CtfSubmission::create([
                'ctf_challenge_id' => $challenge->id,
                'user_id' => $user->id,
                'submitted_flag' => $submittedFlag,
                'is_correct' => false,
                'points_awarded' => 0,
                'attempt_number' => $this->getAttemptNumber($challenge, $user),
                'ip_address' => $ipAddress,
                'submitted_at' => now(),
            ]);

            return ['success' => false, 'error' => 'incorrect', 'message' => 'Incorrect flag'];
        }

        // 3. Award points and record solve
        $submission = CtfSubmission::create([
            'ctf_challenge_id' => $challenge->id,
            'user_id' => $user->id,
            'submitted_flag' => $submittedFlag, // or masked
            'is_correct' => true,
            'points_awarded' => $challenge->points,
            'attempt_number' => $this->getAttemptNumber($challenge, $user),
            'ip_address' => $ipAddress,
            'submitted_at' => now(),
        ]);

        // 4. Award points via GamificationService (Phase 7)
        $this->gamificationService->awardPoints(
            $user,
            $challenge->points,
            "CTF Challenge solved: {$challenge->title}",
            CtfChallenge::class,
            $challenge->id
        );

        // 5. Check for CTF-specific badges
        $this->checkCtfBadges($user, $challenge->ctfCompetition);

        return [
            'success' => true,
            'points' => $challenge->points,
            'total_points' => $user->total_points,
        ];
    }

    public function getUserSolves(CtfCompetition $competition, User $user): Collection
    {
        return CtfSubmission::correct()
            ->forChallenge($competition->challenges()->pluck('id')->toArray())
            ->byUser($user)
            ->get();
    }

    public function getAttemptNumber(CtfChallenge $challenge, User $user): int
    {
        return CtfSubmission::forChallenge($challenge)
            ->byUser($user)
            ->count() + 1;
    }

    protected function checkCtfBadges(User $user, CtfCompetition $competition): Collection
    {
        // Check for CTF badges — delegate to GamificationService
        // which will check Badge::checkCriteria for ctf_completed type
        return $this->gamificationService->checkBadges($user);
    }

    // Admin methods
    public function createChallenge(array $data): CtfChallenge
    {
        $data['flag_hash'] = hash('sha256', $data['flag']);
        unset($data['flag']);

        return CtfChallenge::create($data);
    }

    public function updateChallenge(CtfChallenge $challenge, array $data): CtfChallenge
    {
        if (isset($data['flag'])) {
            $data['flag_hash'] = hash('sha256', $data['flag']);
            unset($data['flag']);
        }
        $challenge->update($data);

        return $challenge;
    }
}
