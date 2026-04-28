@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Competition header --}}
    <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="grid gap-6 xl:grid-cols-[1fr_0.8fr]">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-500">CTF Competition</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $competition->title }}</h1>
                <p class="mt-4 text-sm leading-7 text-gray-600 dark:text-gray-400">{{ $competition->description }}</p>
                @if ($competition->start_date || $competition->end_date)
                <div class="mt-4 flex flex-wrap gap-4 text-xs text-gray-500 dark:text-gray-400">
                    @if ($competition->start_date) <span>Start: {{ $competition->start_date->format('M d, H:i') }}</span> @endif
                    @if ($competition->end_date) <span>End: {{ $competition->end_date->format('M d, H:i') }}</span> @endif
                    @if ($competition->max_score) <span>Max: {{ $competition->max_score }} pts</span> @endif
                </div>
                @endif
            </div>
            <div class="flex flex-col items-end gap-3">
                <a href="{{ route('ctf.scoreboard', $competition) }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-200">
                    View Full Scoreboard
                </a>
            </div>
        </div>
    </section>

    {{-- Flash messages --}}
    @if (session('status'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-800 dark:bg-emerald-900/20">
            <p class="text-sm text-emerald-700 dark:text-emerald-300">{{ session('status') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
            <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Two-column layout: challenges + mini scoreboard --}}
    <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        {{-- Challenges by category --}}
        <div class="space-y-6">
            @forelse ($challengesByCategory as $categoryName => $challenges)
            <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $categoryName }}</span>
                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $challenges->count() }} challenges</span>
                </h2>
                <div class="mt-4 space-y-3">
                    @foreach ($challenges as $challenge)
                    @php
                        $isSolved = in_array($challenge->id, $userSolved);
                        $difficultyColors = ['easy' => 'green', 'medium' => 'yellow', 'hard' => 'orange', 'insane' => 'red'];
                        $difficultyColor = $difficultyColors[$challenge->difficulty] ?? 'gray';
                    @endphp
                    <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-900/40 {{ $isSolved ? 'opacity-60' : '' }}">
                        <div class="flex items-start gap-3">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $challenge->title }}</h3>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center rounded-md bg-{{ $difficultyColor }}-500/10 px-2 py-0.5 text-xs font-medium text-{{ $difficultyColor }}-500">
                                        {{ $challenge->difficulty }}
                                    </span>
                                    @if ($isSolved)
                                        <span class="inline-flex items-center rounded-md bg-emerald-500/10 px-2 py-0.5 text-xs font-medium text-emerald-500">Solved ✓</span>
                                    @endif
                                </div>
                                @if (!$isSolved)
                                <form method="POST" action="{{ route('ctf.submit', ['competition' => $competition, 'challenge' => $challenge]) }}" class="mt-2 flex gap-2">
                                    @csrf
                                    <input type="text" name="flag" placeholder="CTF{flag}" class="rounded-md border border-gray-300 px-2 py-1 text-xs dark:border-gray-700 dark:bg-gray-800" />
                                    <button type="submit" class="rounded-md bg-emerald-500 px-2 py-1 text-xs font-semibold text-white hover:bg-emerald-600">Submit</button>
                                </form>
                                @else
                                <a href="{{ route('ctf.writeup', ['competition' => $competition, 'challenge' => $challenge]) }}" class="mt-2 inline-block text-xs text-emerald-600 hover:text-emerald-700">Submit Writeup</a>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $challenge->points }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">pts</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @empty
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-12 text-center dark:border-gray-800 dark:bg-gray-900/60">
                <p class="text-gray-600 dark:text-gray-400">No active challenges in this competition.</p>
            </div>
            @endforelse
        </div>

        {{-- Mini scoreboard --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Top Players</h2>
            <div class="mt-4 space-y-3">
                @forelse ($scoreboard->take(5) as $entry)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-slate-900 text-xs font-semibold text-white dark:bg-emerald-500 dark:text-slate-950">{{ $entry['rank'] }}</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $entry['name'] }}</span>
                    </div>
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $entry['total_score'] }} pts</span>
                </div>
                @empty
                <p class="text-sm text-gray-500 dark:text-gray-400">No solves yet. Be the first!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection