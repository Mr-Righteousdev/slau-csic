@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] md:p-8">
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-500">CTF Arena</p>
        <h1 class="mt-3 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">Capture The Flag Competitions</h1>
        <p class="mt-4 max-w-3xl text-sm leading-7 text-gray-600 dark:text-gray-400">
            Compete in cybersecurity challenges across multiple categories. Solve challenges, earn points, and climb the scoreboard.
        </p>
    </section>

    @forelse ($competitions as $competition)
    <article class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center rounded-md bg-emerald-500/10 px-2 py-1 text-xs font-semibold text-emerald-500">
                        {{ $competition->status }}
                    </span>
                    @if (!$competition->is_public)
                        <span class="inline-flex items-center rounded-md bg-amber-500/10 px-2 py-1 text-xs font-semibold text-amber-500">
                            Invite Only
                        </span>
                    @endif
                </div>
                <h2 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                    <a href="{{ route('ctf.competition', $competition) }}">{{ $competition->title }}</a>
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $competition->description }}</p>
                <div class="mt-3 flex flex-wrap gap-4 text-xs text-gray-500 dark:text-gray-400">
                    @if ($competition->start_date)
                        <span>Starts: {{ $competition->start_date->format('M d, Y') }}</span>
                    @endif
                    @if ($competition->end_date)
                        <span>Ends: {{ $competition->end_date->format('M d, Y') }}</span>
                    @endif
                    <span>{{ $competition->challenges_count }} challenges</span>
                    <span>{{ $competition->solvedCount }} solved</span>
                </div>
            </div>
            <div class="text-right">
                <a href="{{ route('ctf.competition', $competition) }}" class="inline-flex items-center rounded-md bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-slate-950 hover:bg-emerald-400">
                    {{ $competition->currentlyActive ? 'Enter' : ($competition->start_date?->isFuture() ? 'Upcoming' : 'View') }}
                </a>
            </div>
        </div>
    </article>
    @empty
    <div class="rounded-lg border border-gray-200 bg-gray-50 p-12 text-center dark:border-gray-800 dark:bg-gray-900/60">
        <p class="text-gray-600 dark:text-gray-400">No active CTF competitions yet. Check back soon!</p>
    </div>
    @endforelse
</div>
@endsection