<footer class="border-t border-gray-800/60 bg-black/40 mt-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid gap-8 md:grid-cols-4">
            <div class="md:col-span-2 space-y-3">
                <h3 class="text-lg font-semibold text-white">Cybersecurity & Innovations Club</h3>
                <p class="text-sm text-gray-400 max-w-md">
                    A student community at St. Lawrence University (SLAU) passionate about ethical hacking,
                    digital forensics, secure coding, and emerging technologies.
                </p>
                <p class="text-xs text-gray-500">Made with Laravel & Tailwind CSS.</p>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-300 mb-3">Quick Links</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('about') }}" class="hover:text-emerald-400">About the Club</a></li>
                    <li><a href="{{ route('events') }}" class="hover:text-emerald-400">Upcoming Events</a></li>
                    <li><a href="{{ route('team') }}" class="hover:text-emerald-400">Leadership Team</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-emerald-400">Contact</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-300 mb-3">Stay Connected</h4>
                <p class="text-sm text-gray-400 mb-3">Follow our activities and join discussions:</p>
                <div class="flex items-center gap-3">
                    <a href="#" class="h-9 w-9 rounded-full border border-gray-700 flex items-center justify-center text-gray-400 hover:border-emerald-400 hover:text-emerald-400 text-xs">
                        X
                    </a>
                    <a href="#" class="h-9 w-9 rounded-full border border-gray-700 flex items-center justify-center text-gray-400 hover:border-emerald-400 hover:text-emerald-400 text-xs">
                        IG
                    </a>
                    <a href="#" class="h-9 w-9 rounded-full border border-gray-700 flex items-center justify-center text-gray-400 hover:border-emerald-400 hover:text-emerald-400 text-xs">
                        TG
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-800/60 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
            <p>&copy; {{ date('Y') }} SLAU Cybersecurity & Innovations Club. All rights reserved.</p>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-emerald-400">Code of Conduct</a>
                <a href="#" class="hover:text-emerald-400">Privacy</a>
            </div>
        </div>
    </div>
</footer>
