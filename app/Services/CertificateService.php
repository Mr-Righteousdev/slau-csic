<?php

namespace App\Services;

use App\Models\CertificateEligibility;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Support\Collection;

class CertificateService
{
    public function createEligibility(ExamAttempt $attempt): CertificateEligibility
    {
        if (! $attempt->passed) {
            throw new \InvalidArgumentException('Cannot create eligibility for failed attempt');
        }

        return CertificateEligibility::updateOrCreate(
            ['exam_attempt_id' => $attempt->id],
            [
                'user_id' => $attempt->user_id,
                'exam_id' => $attempt->exam_id,
                'eligible' => true,
            ]
        );
    }

    public function revokeEligibility(CertificateEligibility $eligibility): bool
    {
        $eligibility->eligible = false;
        $timestamp = now()->toDateTimeString();
        if ($eligibility->notes) {
            $eligibility->notes .= "\n[{$timestamp}] Revoked";
        } else {
            $eligibility->notes = "[{$timestamp}] Revoked";
        }

        return $eligibility->save();
    }

    public function getEligibleMembers(Exam $exam): Collection
    {
        return CertificateEligibility::with('user')
            ->where('exam_id', $exam->id)
            ->where('eligible', true)
            ->get();
    }

    public function getUserEligibilities(User $user): Collection
    {
        return CertificateEligibility::with(['exam', 'examAttempt'])
            ->where('user_id', $user->id)
            ->where('eligible', true)
            ->get();
    }
}
