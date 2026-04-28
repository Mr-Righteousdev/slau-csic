<?php

namespace App\Livewire\Exams;

use App\Services\CertificateService;
use Livewire\Component;

class Certificates extends Component
{
    public function getEligibilitiesProperty()
    {
        return app(CertificateService::class)->getUserEligibilities(auth()->user());
    }

    public function render()
    {
        return view('livewire.exams.certificates');
    }
}
