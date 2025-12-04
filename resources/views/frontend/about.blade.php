@extends('layouts.frontend')

@section('content')
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16">
        <header class="mb-8">
            <p class="text-xs font-semibold tracking-wide text-emerald-300 uppercase mb-2">About the Club</p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-3">Cybersecurity & Innovations Club at SLAU</h1>
            <p class="text-sm text-gray-300 max-w-2xl">
                We are a student-driven community focused on learning, practicing, and advocating for
                cybersecurity and responsible innovation in technology within and beyond St. Lawrence
                University (SLAU).
            </p>
        </header>

        <div class="space-y-8 text-sm text-gray-300">
            <section>
                <h2 class="text-base font-semibold text-white mb-2">Our mission</h2>
                <p>
                    Our mission is to empower students with practical cybersecurity skills, foster an
                    environment of ethical hacking and research, and build a collaborative network of
                    learners, mentors, and professionals.
                </p>
            </section>

            <section class="grid sm:grid-cols-2 gap-6">
                <div class="cyber-card rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-white mb-1.5">What you will gain</h3>
                    <ul class="list-disc list-inside space-y-1 text-xs text-gray-300">
                        <li>Foundational knowledge in cyber defense and offensive security.</li>
                        <li>Hands-on experience with widely used security tools.</li>
                        <li>Opportunities to participate in CTFs and competitions.</li>
                        <li>Guidance on certifications and career paths.</li>
                    </ul>
                </div>
                <div class="cyber-card rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-white mb-1.5">Who can join?</h3>
                    <ul class="list-disc list-inside space-y-1 text-xs text-gray-300">
                        <li>Students from any faculty or year of study.</li>
                        <li>No prior experience required—just curiosity.</li>
                        <li>Those interested in security, networking, programming, or tech in general.</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-base font-semibold text-white mb-2">How we operate</h2>
                <ul class="list-disc list-inside space-y-2 text-xs sm:text-sm">
                    <li><span class="font-medium text-gray-100">Weekly meetups:</span> Practical sessions, talks, and labs held on campus or virtually.</li>
                    <li><span class="font-medium text-gray-100">Working groups:</span> Smaller focused teams for topics like web security, reverse engineering, or blue teaming.</li>
                    <li><span class="font-medium text-gray-100">Community-first:</span> We value collaboration, respect, and responsible disclosure practices.</li>
                </ul>
            </section>

            <section class="border border-gray-800/80 rounded-2xl bg-black/40 px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4">
                <div>
                    <h3 class="text-sm font-semibold text-white mb-1">Interested in joining?</h3>
                    <p class="text-xs text-gray-300">Check our events or contact the team—we're always onboarding new members at the start and middle of each semester.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('register') }}" class="cyber-button text-xs sm:text-sm">Sign up</a>
                    <a href="{{ route('events') }}" class="text-xs sm:text-sm font-medium text-emerald-300 hover:text-emerald-200">View events</a>
                </div>
            </section>
        </div>
    </section>
@endsection
