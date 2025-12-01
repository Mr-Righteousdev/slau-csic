@extends('layouts.frontend')

@section('content')
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16">
        <header class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold tracking-wide text-emerald-300 uppercase mb-2">Events</p>
                <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Upcoming sessions & activities</h1>
                <p class="text-sm text-gray-300 max-w-2xl">
                    Workshops, CTFs, info sessions, and meetups organized by the Cybersecurity & Innovations Club.
                    This is a static preview—later you can connect this page to a database or admin panel.
                </p>
            </div>
            <a href="{{ route('contact') }}" class="cyber-button text-xs sm:text-sm">Propose an event</a>
        </header>

        <div class="space-y-4">
            {{-- Example event cards – replace with dynamic data later --}}
            <article class="cyber-card rounded-xl p-5 flex flex-col sm:flex-row gap-4">
                <div class="sm:w-1/4 flex flex-col justify-between gap-2">
                    <div>
                        <p class="text-xs font-semibold text-emerald-300">Workshop</p>
                        <p class="text-sm font-semibold text-white">Intro to Ethical Hacking</p>
                    </div>
                    <div class="text-[11px] text-gray-400">
                        <p>Every Saturday • 2:00 PM - 4:00 PM</p>
                        <p>SLAU Computer Lab / Online</p>
                    </div>
                </div>
                <div class="sm:flex-1 text-xs text-gray-300 space-y-2">
                    <p>Get comfortable with the hacker mindset, common attack surfaces, and core tools. Perfect for beginners who want a safe introduction to security.</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Setting up your lab safely</li>
                        <li>Reconnaissance basics</li>
                        <li>Legal & ethical considerations</li>
                    </ul>
                </div>
            </article>

            <article class="cyber-card rounded-xl p-5 flex flex-col sm:flex-row gap-4">
                <div class="sm:w-1/4 flex flex-col justify-between gap-2">
                    <div>
                        <p class="text-xs font-semibold text-sky-300">Capture the Flag</p>
                        <p class="text-sm font-semibold text-white">SLAU Internal CTF</p>
                    </div>
                    <div class="text-[11px] text-gray-400">
                        <p>Monthly • Friday evening</p>
                        <p>On-campus & Discord</p>
                    </div>
                </div>
                <div class="sm:flex-1 text-xs text-gray-300 space-y-2">
                    <p>Form a team or play solo to tackle challenges in web exploitation, OSINT, cryptography, and forensics. Great preparation for external CTFs.</p>
                    <p class="text-emerald-300">Prizes for top performers and most improved participants.</p>
                </div>
            </article>

            <article class="cyber-card rounded-xl p-5 flex flex-col sm:flex-row gap-4">
                <div class="sm:w-1/4 flex flex-col justify-between gap-2">
                    <div>
                        <p class="text-xs font-semibold text-violet-300">Guest Talk</p>
                        <p class="text-sm font-semibold text-white">Careers in Cybersecurity</p>
                    </div>
                    <div class="text-[11px] text-gray-400">
                        <p>Once per semester</p>
                        <p>Auditorium / Online</p>
                    </div>
                </div>
                <div class="sm:flex-1 text-xs text-gray-300 space-y-2">
                    <p>Industry professionals share their journeys, what they look for in junior roles, and how to build a strong security portfolio while still in school.</p>
                    <p>Follow our social media for exact dates and speaker announcements.</p>
                </div>
            </article>
        </div>
    </section>
@endsection
