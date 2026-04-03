<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;

class PublicMemberPageController extends Controller
{
    public function index(): View
    {
        $members = User::query()
            ->forDirectory()
            ->with(['roles', 'projects', 'projectMemberships.project', 'competitionParticipations.competition'])
            ->orderByDesc('approved_at')
            ->orderBy('name')
            ->get();

        return view('frontend.members', [
            'title' => 'Club Members - Cybersecurity & Innovations Club',
            'members' => $members,
            'featuredMembers' => $members->take(6),
        ]);
    }

    public function show(User $user): View
    {
        abort_unless($user->isApproved() && $user->show_profile, 404);

        $user->load([
            'roles',
            'projects',
            'projectMemberships.project',
            'competitionParticipations.competition',
        ]);

        return view('frontend.member-profile', [
            'title' => $user->name.' - Member Profile',
            'member' => $user,
            'stats' => $user->getMemberStats(),
        ]);
    }
}
