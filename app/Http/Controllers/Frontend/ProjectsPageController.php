<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class ProjectsPageController extends Controller
{
    public function index(): View
    {
        $projects = Project::query()
            ->with(['lead', 'members'])
            ->latest()
            ->get();

        $deliveryPillars = [
            [
                'title' => 'Challenge Framing',
                'copy' => 'Each project starts with a clearly defined security, innovation, or campus problem so members understand the mission before they begin building.',
            ],
            [
                'title' => 'Applied Execution',
                'copy' => 'Teams move from planning to hands-on implementation through secure coding, lab testing, design reviews, and documented iteration.',
            ],
            [
                'title' => 'Visible Outcomes',
                'copy' => 'The club presents work as outcomes, lessons, and contribution records so progress is visible to members, leaders, and partners.',
            ],
        ];

        $projectTracks = [
            [
                'name' => 'Secure Build Reviews',
                'focus' => 'Student teams examine web and mobile ideas through a security-first delivery lens.',
            ],
            [
                'name' => 'Campus Problem Solving',
                'focus' => 'Projects are framed around practical institutional needs, operational clarity, and responsible digital practice.',
            ],
            [
                'name' => 'Competition Readiness',
                'focus' => 'Members refine tools, challenge workflows, and team coordination before club competitions and external events.',
            ],
        ];

        return view('frontend.projects', [
            'title' => 'Projects - Cybersecurity & Innovations Club',
            'projects' => $projects,
            'deliveryPillars' => $deliveryPillars,
            'projectTracks' => $projectTracks,
        ]);
    }
}
