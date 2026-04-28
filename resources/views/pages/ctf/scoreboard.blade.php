@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-500">CTF Scoreboard</p>
                <h1 class="mt-3 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $competition->title }}</h1>
            </div>
            <a href="{{ route('ctf.competition', $competition) }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-200">
                Back to Competition
            </a>
        </div>
    </section>

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="pb-3 text-left font-medium text-gray-500 w-16">Rank</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Player</th>
                    <th class="pb-3 text-right font-medium text-gray-500">Score</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($scoreboard as $entry)
                <tr class="border-b border-gray-100 dark:border-gray-800 {{ $entry['user_id'] === auth()->id() ? 'bg-emerald-50 dark:bg-emerald-900/10' : '' }}">
                    <td class="py-3">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-slate-900 text-xs font-semibold text-white
                            @if($entry['rank'] === 1) bg-yellow-500
                            @elseif($entry['rank'] === 2) bg-gray-400
                            @elseif($entry['rank'] === 3) bg-amber-700 @endif">
                            {{ $entry['rank'] }}
                        </span>
                    </td>
                    <td class="py-3 font-medium text-gray-900 dark:text-white">{{ $entry['name'] }}</td>
                    <td class="py-3 text-right font-semibold text-gray-900 dark:text-white">{{ $entry['total_score'] }} pts</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-8 text-center text-gray-500">No scores yet. Be the first to solve!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($userRank)
    <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-800 dark:bg-emerald-900/20">
        <p class="text-sm text-emerald-700 dark:text-emerald-300">Your rank: <strong>#{{ $userRank }}</strong></p>
    </div>
    @endif
</div>
@endsection