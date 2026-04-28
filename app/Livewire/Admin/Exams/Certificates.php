<?php

namespace App\Livewire\Admin\Exams;

use App\Models\CertificateEligibility;
use App\Models\Exam;
use App\Services\CertificateService;
use Livewire\Component;
use Livewire\WithPagination;

class Certificates extends Component
{
    use WithPagination;

    public ?int $examFilter = null;

    public function getEligibilitiesProperty()
    {
        $query = CertificateEligibility::with(['user', 'exam', 'examAttempt']);

        if ($this->examFilter) {
            $query->where('exam_id', $this->examFilter);
        }

        return $query->orderByDesc('created_at')->paginate(20);
    }

    public function getExamsProperty()
    {
        return Exam::whereHas('certificateEligibilities')
            ->orWhereHas('attempts', fn ($q) => $q->where('passed', true))
            ->orderBy('title')
            ->get();
    }

    public function revoke(int $eligibilityId): void
    {
        $eligibility = CertificateEligibility::find($eligibilityId);
        if ($eligibility) {
            app(CertificateService::class)->revokeEligibility($eligibility);
        }
    }

    public function render()
    {
        return view('livewire.admin.exams.certificates');
    }
}
