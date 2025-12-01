@extends('layouts.frontend')

@section('content')
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16">
        <header class="mb-8">
            <p class="text-xs font-semibold tracking-wide text-emerald-300 uppercase mb-2">Contact</p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-3">Get in touch with the organizers</h1>
            <p class="text-sm text-gray-300 max-w-2xl">
                Questions about membership, events, collaborations, or speaking opportunities? Use the form
                below or reach out via email/socials. You can wire this form up to a real backend later.
            </p>
        </header>

        <div class="grid gap-8 md:grid-cols-[minmax(0,_3fr)_minmax(0,_2fr)] items-start">
            <form class="cyber-card rounded-2xl p-5 space-y-4">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-300 mb-1">Full name</label>
                        <input id="name" type="text" class="w-full rounded-lg border border-gray-800 bg-black/40 px-3 py-2 text-sm text-gray-100 focus:outline-none focus:ring-1 focus:ring-emerald-400 focus:border-emerald-400" placeholder="Your name" />
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-300 mb-1">Email</label>
                        <input id="email" type="email" class="w-full rounded-lg border border-gray-800 bg-black/40 px-3 py-2 text-sm text-gray-100 focus:outline-none focus:ring-1 focus:ring-emerald-400 focus:border-emerald-400" placeholder="you@example.com" />
                    </div>
                </div>

                <div>
                    <label for="topic" class="block text-xs font-medium text-gray-300 mb-1">Topic</label>
                    <select id="topic" class="w-full rounded-lg border border-gray-800 bg-black/40 px-3 py-2 text-sm text-gray-100 focus:outline-none focus:ring-1 focus:ring-emerald-400 focus:border-emerald-400">
                        <option>Membership / joining</option>
                        <option>Event or workshop idea</option>
                        <option>Collaboration or sponsorship</option>
                        <option>Other</option>
                    </select>
                </div>

                <div>
                    <label for="message" class="block text-xs font-medium text-gray-300 mb-1">Message</label>
                    <textarea id="message" rows="4" class="w-full rounded-lg border border-gray-800 bg-black/40 px-3 py-2 text-sm text-gray-100 focus:outline-none focus:ring-1 focus:ring-emerald-400 focus:border-emerald-400" placeholder="Tell us how we can help"></textarea>
                </div>

                <p class="text-[11px] text-gray-400">By submitting, you agree to be contacted by the club organizers via email.</p>

                <button type="button" class="cyber-button text-sm w-full sm:w-auto">Send message (static)</button>
            </form>

            <aside class="space-y-5 text-sm text-gray-300">
                <div>
                    <h2 class="text-sm font-semibold text-white mb-1">Club contact</h2>
                    <p class="text-xs text-gray-300">Update these details with your actual contact info.</p>
                    <ul class="mt-2 space-y-1 text-xs">
                        <li><span class="text-gray-400">Email:</span> <span class="text-emerald-300">cyberclub@slau.ac.ug</span></li>
                        <li><span class="text-gray-400">Location:</span> SLAU Campus, Kampala, Uganda</li>
                        <li><span class="text-gray-400">Discord / WhatsApp:</span> To be announced</li>
                    </ul>
                </div>

                <div class="border border-gray-800/80 rounded-xl bg-black/40 px-4 py-3 text-xs text-gray-300">
                    <h3 class="text-xs font-semibold text-white mb-1">Office hours</h3>
                    <p>We usually respond within a few days, especially around the start of the semester when onboarding is active.</p>
                </div>

                <div class="border border-amber-400/20 bg-amber-500/5 rounded-xl px-4 py-3 text-xs text-amber-100">
                    <h3 class="font-semibold mb-1">Responsible security use only</h3>
                    <p>All activities under this club emphasize ethical behavior, respect for privacy, and adherence to university and legal policies.</p>
                </div>
            </aside>
        </div>
    </section>
@endsection
