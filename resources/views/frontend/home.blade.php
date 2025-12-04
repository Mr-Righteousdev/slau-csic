@extends('layouts.frontend')

@section('content')
    {{-- Hero section --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 opacity-30 pointer-events-none" aria-hidden="true">
            <div class="absolute -inset-[40%] bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.4),_transparent_60%),_radial-gradient(circle_at_bottom,_rgba(56,189,248,0.35),_transparent_55%)]"></div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-20 relative">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-black/40 px-3 py-1 text-xs font-medium text-emerald-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping-slow"></span>
                        Active student community
                    </span>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white leading-tight">
                        Building the next generation of
                        <span class="cyber-text-gradient">ethical hackers & security engineers</span>
                    </h1>

                    <p class="text-gray-300 text-sm sm:text-base max-w-xl">
                        Join the Cybersecurity & Innovations Club at St. Lawrence University (SLAU) to explore
                        ethical hacking, digital forensics, secure coding, and cutting-edge technologies through
                        hands-on workshops and real-world projects.
                    </p>

                    <div class="flex flex-wrap items-center gap-4">
                        <a href="{{ route('register') }}" class="cyber-button flex items-center gap-2">
                            <span>Join the Club</span>
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('events') }}" class="text-sm font-medium text-gray-200 hover:text-emerald-400 flex items-center gap-2">
                            View upcoming events
                            <span class="text-xs text-emerald-400 border border-emerald-400/50 rounded-full px-2 py-0.5">CTFs • Workshops • Talks</span>
                        </a>
                    </div>

                    <dl class="grid grid-cols-3 gap-4 pt-4 max-w-md text-xs sm:text-sm">
                        <div>
                            <dt class="text-gray-400">Active members</dt>
                            <dd class="text-emerald-400 font-semibold text-lg">50+ </dd>
                        </div>
                        <div>
                            <dt class="text-gray-400">Events / semester</dt>
                            <dd class="text-sky-400 font-semibold text-lg">10+</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400">Universities engaged</dt>
                            <dd class="text-violet-400 font-semibold text-lg">3+</dd>
                        </div>
                    </dl>
                </div>

                <div class="relative">
                    <div class="cyber-card rounded-2xl p-6 sm:p-7 lg:p-8 relative overflow-hidden">
                        <div class="absolute inset-x-0 -top-20 h-40 bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.5),_transparent_65%)] opacity-70"></div>

                        <div class="relative space-y-5">
                            <div class="flex items-center justify-between">
                                <h2 class="text-sm font-semibold text-gray-100">Upcoming Session</h2>
                                <span class="inline-flex items-center gap-1.5 text-[11px] px-2 py-1 rounded-full bg-emerald-400/10 text-emerald-300 border border-emerald-400/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    Open to all SLAU students
                                </span>
                            </div>

                            <div class="space-y-1 text-xs">
                                <p class="text-gray-300 font-medium">Introduction to Web Application Security</p>
                                <p class="text-gray-400">Hands-on session covering OWASP Top 10, basic recon, and secure coding practices.</p>
                            </div>

                            <div class="grid grid-cols-3 gap-3 text-xs">
                                <div class="rounded-lg border border-gray-800/80 bg-black/40 px-3 py-2">
                                    <div class="text-[10px] uppercase tracking-wide text-gray-500">Date</div>
                                    <div class="text-gray-100 font-medium">Every Saturday</div>
                                </div>
                                <div class="rounded-lg border border-gray-800/80 bg-black/40 px-3 py-2">
                                    <div class="text-[10px] uppercase tracking-wide text-gray-500">Venue</div>
                                    <div class="text-gray-100 font-medium">SLAU Lab / Online</div>
                                </div>
                                <div class="rounded-lg border border-gray-800/80 bg-black/40 px-3 py-2">
                                    <div class="text-[10px] uppercase tracking-wide text-gray-500">Level</div>
                                    <div class="text-gray-100 font-medium">Beginner Friendly</div>
                                </div>
                            </div>

                            <div class="border-t border-gray-800/80 pt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-emerald-400/20 border border-emerald-400/40 flex items-center justify-center text-xs font-semibold text-emerald-200">CTF</div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-100">Monthly Capture the Flag</p>
                                        <p class="text-[11px] text-gray-400">Form teams, solve challenges, and win club swag.</p>
                                    </div>
                                </div>

                                <a href="{{ route('events') }}" class="text-[11px] font-medium text-emerald-300 hover:text-emerald-200 flex items-center gap-1">
                                    View full events calendar
                                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- What we do section --}}
    <section class="mt-6 sm:mt-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1 space-y-3">
                    <h2 class="section-title text-2xl sm:text-3xl text-white">What we do</h2>
                    <p class="text-sm text-gray-300 max-w-md">
                        We combine theory with practice through peer learning, labs, and real-world oriented
                        projects. No prior experience is required—just curiosity and willingness to learn.
                    </p>
                </div>

                <div class="lg:col-span-2 grid sm:grid-cols-2 gap-6">
                    <div class="cyber-card rounded-xl p-5">
                        <h3 class="text-sm font-semibold text-white mb-1.5">Hands-on Workshops</h3>
                        <p class="text-xs text-gray-300 mb-2">Weekly guided sessions in topics like:</p>
                        <ul class="text-xs text-gray-300 space-y-1 list-disc list-inside">
                            <li>Linux basics &amp; command line</li>
                            <li>Web application security &amp; OWASP Top 10</li>
                            <li>Network security &amp; traffic analysis</li>
                        </ul>
                    </div>
                    <div class="cyber-card rounded-xl p-5">
                        <h3 class="text-sm font-semibold text-white mb-1.5">Capture the Flag (CTF)</h3>
                        <p class="text-xs text-gray-300 mb-2">Challenge-based learning through:</p>
                        <ul class="text-xs text-gray-300 space-y-1 list-disc list-inside">
                            <li>Web exploitation &amp; OSINT</li>
                            <li>Reverse engineering &amp; forensics</li>
                            <li>Crackmes, crypto, and more</li>
                        </ul>
                    </div>
                    <div class="cyber-card rounded-xl p-5">
                        <h3 class="text-sm font-semibold text-white mb-1.5">Talks &amp; Mentorship</h3>
                        <p class="text-xs text-gray-300 mb-2">We invite professionals to share about:</p>
                        <ul class="text-xs text-gray-300 space-y-1 list-disc list-inside">
                            <li>Careers in cybersecurity</li>
                            <li>Industry tools &amp; certifications</li>
                            <li>Building a security portfolio</li>
                        </ul>
                    </div>
                    <div class="cyber-card rounded-xl p-5">
                        <h3 class="text-sm font-semibold text-white mb-1.5">Projects &amp; Research</h3>
                        <p class="text-xs text-gray-300 mb-2">Collaborate on security-focused projects such as:</p>
                        <ul class="text-xs text-gray-300 space-y-1 list-disc list-inside">
                            <li>Security tooling &amp; automation</li>
                            <li>Threat intelligence dashboards</li>
                            <li>Awareness campaigns on campus</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA section --}}
    <section class="mt-14 mb-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="cyber-card rounded-2xl px-6 py-7 sm:px-8 sm:py-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg sm:text-xl font-semibold text-white mb-1">Ready to start your security journey?</h2>
                    <p class="text-sm text-gray-300 max-w-xl">Come to our next meeting or sign up online—whether you're a beginner or already hacking on your own, there's a place for you here.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('register') }}" class="cyber-button text-sm">Become a member</a>
                    <a href="{{ route('contact') }}" class="text-xs sm:text-sm font-medium text-gray-200 hover:text-emerald-400">Contact the organizers</a>
                </div>
            </div>
        </div>
    </section>
@endsection
