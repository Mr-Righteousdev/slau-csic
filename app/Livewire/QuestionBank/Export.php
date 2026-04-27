<?php

namespace App\Livewire\QuestionBank;

use App\Models\QuestionBankQuestion;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Export extends Component
{
    public function exportAll(): StreamedResponse
    {
        $questions = QuestionBankQuestion::with('options')->get();

        $data = [
            'exam_title' => 'Question Bank Export',
            'version' => 1,
            'questions' => $questions->map(fn ($q) => $q->toExamShieldFormat())->toArray(),
        ];

        $filename = 'question_bank_'.now()->format('Ymd_His').'.json';

        return response()->streamDownload(
            fn () => fwrite(fopen('php://output', 'w'), json_encode($data, JSON_PRETTY_PRINT)),
            $filename,
            ['Content-Type' => 'application/json']
        );
    }

    public function render()
    {
        $count = QuestionBankQuestion::count();

        return view('livewire.question-bank.export', compact('count'));
    }
}
