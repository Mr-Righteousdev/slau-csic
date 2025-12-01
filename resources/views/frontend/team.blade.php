@extends('layouts.frontend')

@section('content')
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16">
        <header class="mb-8 text-center">
            <p class="text-xs font-semibold tracking-wide text-emerald-300 uppercase mb-2">Leadership</p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-3">Meet the organizing team</h1>
            <p class="text-sm text-gray-300 max-w-2xl mx-auto">
                The Cybersecurity & Innovations Club is led by dedicated students who coordinate learning
                tracks, events, and partnerships. Update these profiles with your actual leaders.
            </p>
        </header>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Example team cards – replace with real data --}}
            <article class="cyber-card rounded-xl p-5 flex flex-col items-center text-center gap-3">
                <div class="h-16 w-16 rounded-full bg-emerald-400/20 border border-emerald-400/40 flex items-center justify-center text-lg font-semibold text-emerald-200">
                    P
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-white">Club President</h2>
                    <p class="text-xs text-gray-400">Overall coordination &amp; external relations.</p>
                </div>
                <ul class="text-[11px] text-gray-300 space-y-1">
                    <li>Leads strategy and semester planning.</li>
                    <li>Represents the club to faculty and partners.</li>
                </ul>
            </article>

            <article class="cyber-card rounded-xl p-5 flex flex-col items-center text-center gap-3">
                <div class="h-16 w-16 rounded-full bg-sky-400/20 border border-sky-400/40 flex items-center justify-center text-lg font-semibold text-sky-200">
                    V
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-white">Vice President</h2>
                    <p class="text-xs text-gray-400">Program design &amp; club operations.</p>
                </div>
                <ul class="text-[11px] text-gray-300 space-y-1">
                    <li>Helps run weekly sessions.</li>
                    <li>Coordinates with working group leads.</li>
                </ul>
            </article>

            <article class="cyber-card rounded-xl p-5 flex flex-col items-center text-center gap-3">
                <div class="h-16 w-16 rounded-full bg-violet-400/20 border border-violet-400/40 flex items-center justify-center text-lg font-semibold text-violet-200">
                    T
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-white">Technical Lead</h2>
                    <p class="text-xs text-gray-400">Labs, tools, and learning tracks.</p>
                </div>
                <ul class="text-[11px] text-gray-300 space-y-1">
                    <li>Prepares labs &amp; CTF challenges.</li>
                    <li>Mentors new members on fundamentals.</li>
                </ul>
            </article>

            <article class="cyber-card rounded-xl p-5 flex flex-col items-center text-center gap-3">
                <div class="h-16 w-16 rounded-full bg-amber-400/20 border border-amber-400/40 flex items-center justify-center text-lg font-semibold text-amber-200">
                    C
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-white">Community &amp; Comms Lead</h2>
                    <p class="text-xs text-gray-400">Onboarding &amp; communication.</p>
                </div>
                <ul class="text-[11px] text-gray-300 space-y-1">
                    <li>Manages announcements and social media.</li>
                    <li>Ensures an inclusive, welcoming environment.</li>
                </ul>
            </article>

            <article class="cyber-card rounded-xl p-5 flex flex-col items-center text-center gap-3">
                <div class="h-16 w-16 rounded-full bg-rose-400/20 border border-rose-400/40 flex items-center justify-center text-lg font-semibold text-rose-200">
                    S
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-white">Secretariat</h2>
                    <p class="text-xs text-gray-400">Membership &amp; records.</p>
                </div>
                <ul class="text-[11px] text-gray-300 space-y-1">
                    <li>Keeps attendance and membership lists.</li>
                    <li>Tracks feedback from members.</li>
                </ul>
            </article>

            <article class="cyber-card rounded-xl p-5 flex flex-col items-center text-center gap-3">
                <div class="h-16 w-16 rounded-full bg-teal-400/20 border border-teal-400/40 flex items-center justify-center text-lg font-semibold text-teal-200">
                    F
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-white">Faculty Patron / Advisor</h2>
                    <p class="text-xs text-gray-400">Staff mentor (optional placeholder).</p>
                </div>
                <ul class="text-[11px] text-gray-300 space-y-1">
                    <li>Supports alignment with university policies.</li>
                    <li>Helps connect with external speakers.</li>
                </ul>
            </article>
        </div>
    </section>
@endsection
