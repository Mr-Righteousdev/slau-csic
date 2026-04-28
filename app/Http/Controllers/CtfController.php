<?php

namespace App\Http\Controllers;

use App\Models\CtfChallenge;
use App\Models\CtfCompetition;
use App\Models\CtfSubmission;
use App\Models\CtfWriteup;
use App\Services\CtfScoreboardService;
use App\Services\CtfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CtfController extends Controller
{
    public function __construct(
        protected CtfService $ctfService,
        protected CtfScoreboardService $scoreboardService,
    ) {}

    public function index(): View
    {
        $competitions = CtfCompetition::published()
            ->public()
            ->orderByDesc('start_date')
            ->withCount('challenges')
            ->get()
            ->map(function (CtfCompetition $c) {
                $c->solvedCount = $c->challenges()
                    ->whereHas('submissions', fn ($q) => $q->where('user_id', auth()->id())->where('is_correct', true))
                    ->count();

                return $c;
            });

        return view('pages.ctf.index', [
            'title' => 'CTF Arena',
            'competitions' => $competitions,
        ]);
    }

    public function show(CtfCompetition $competition): View
    {
        abort_unless($competition->status === 'published' || auth()->user()?->isAdmin(), 403);

        $challenges = $competition->challenges()
            ->where('is_active', true)
            ->with('category')
            ->orderBy('sort_order')
            ->orderBy('points')
            ->get()
            ->groupBy(fn ($c) => $c->category?->name ?? 'Uncategorized');

        $userSolved = CtfSubmission::where('user_id', auth()->id())
            ->whereIn('ctf_challenge_id', $competition->challenges()->pluck('id'))
            ->where('is_correct', true)
            ->pluck('ctf_challenge_id')
            ->toArray();

        $scoreboard = $this->scoreboardService->getScoreboard($competition, 10);

        return view('pages.ctf.competition', [
            'title' => $competition->title,
            'competition' => $competition,
            'challengesByCategory' => $challenges,
            'userSolved' => $userSolved,
            'scoreboard' => $scoreboard,
        ]);
    }

    public function submit(Request $request, CtfCompetition $competition, CtfChallenge $challenge): RedirectResponse
    {
        $request->validate(['flag' => 'required|string|min:3|max:255']);

        $result = $this->ctfService->submitFlag(
            $challenge,
            auth()->user(),
            $request->input('flag'),
            $request->ip()
        );

        if ($result['success']) {
            return redirect()
                ->back()
                ->with('status', "Correct! +{$result['points']} points. Total: {$result['total_points']}");
        }

        return redirect()
            ->back()
            ->with('error', $result['message'])
            ->withInput();
    }

    public function scoreboard(CtfCompetition $competition): View
    {
        $scoreboard = $this->scoreboardService->getScoreboard($competition, 100);
        $userRank = auth()->check()
            ? $this->scoreboardService->getUserRank($competition, auth()->user())
            : null;

        return view('pages.ctf.scoreboard', [
            'title' => "{$competition->title} — Scoreboard",
            'competition' => $competition,
            'scoreboard' => $scoreboard,
            'userRank' => $userRank,
        ]);
    }

    public function writeup(CtfCompetition $competition, CtfChallenge $challenge): View
    {
        abort_unless($challenge->isSolvedBy(auth()->user()), 403);

        $existingWriteup = CtfWriteup::where('ctf_challenge_id', $challenge->id)
            ->where('user_id', auth()->id())
            ->first();

        return view('pages.ctf.writeup', [
            'title' => "Writeup: {$challenge->title}",
            'competition' => $competition,
            'challenge' => $challenge,
            'existingWriteup' => $existingWriteup,
        ]);
    }

    public function submitWriteup(Request $request, CtfCompetition $competition, CtfChallenge $challenge): RedirectResponse
    {
        abort_unless($challenge->isSolvedBy(auth()->user()), 403);

        $request->validate(['content' => 'required|string|min:100|max:50000']);

        CtfWriteup::updateOrCreate(
            ['ctf_challenge_id' => $challenge->id, 'user_id' => auth()->id()],
            ['content' => $request->input('content'), 'status' => 'pending']
        );

        return redirect()
            ->route('ctf.competition', $competition)
            ->with('status', 'Writeup submitted for review.');
    }
}
