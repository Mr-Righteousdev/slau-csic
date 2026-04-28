<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\ExamQuestion;
use App\Models\User;

class ExamAttemptService
{
    public function startAttempt(Exam $exam, User $user): ExamAttempt
    {
        // Check if user already has an attempt
        $existingAttempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingAttempt) {
            return $existingAttempt;
        }

        return ExamAttempt::create([
            'exam_id' => $exam->id,
            'user_id' => $user->id,
            'started_at' => now(),
            'time_remaining_seconds' => $exam->duration_minutes * 60,
        ]);
    }

    public function submitAttempt(ExamAttempt $attempt): array
    {
        $attempt->update([
            'submitted_at' => now(),
            'time_remaining_seconds' => 0,
        ]);

        return $this->calculateScore($attempt);
    }

    public function saveAnswer(ExamAttempt $attempt, ExamQuestion $examQuestion, array $data): ExamAnswer
    {
        $answer = ExamAnswer::updateOrCreate(
            [
                'exam_attempt_id' => $attempt->id,
                'exam_question_id' => $examQuestion->id,
            ],
            [
                'answer_text' => $data['answer_text'] ?? null,
                'selected_option_id' => $data['selected_option_id'] ?? null,
            ]
        );

        return $answer;
    }

    public function calculateScore(ExamAttempt $attempt): array
    {
        $totalScore = 0;
        $totalQuestions = $attempt->answers->count();
        $answers = [];

        foreach ($attempt->answers as $answer) {
            $totalScore += $answer->marks_awarded ?? 0;
            $answers[] = [
                'question_id' => $answer->exam_question_id,
                'marks_awarded' => $answer->marks_awarded,
                'is_correct' => $answer->is_correct,
            ];
        }

        $passed = $attempt->exam->passing_score > 0
            ? ($totalScore / ($attempt->exam->passing_score * $totalQuestions / 100)) >= 1
            : false;

        $attempt->update([
            'total_score' => $totalScore,
            'passed' => $passed,
        ]);

        return [
            'total_score' => $totalScore,
            'passed' => $passed,
            'answers' => $answers,
        ];
    }

    public function getUserAttempt(Exam $exam, User $user): ?ExamAttempt
    {
        return ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->first();
    }
}
