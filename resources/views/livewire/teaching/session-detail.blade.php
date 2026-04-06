<div>
    <x-common.page-breadcrumb :pageTitle="$meeting->title" />

    @if(session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span class="text-red-800">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $meeting->title }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $meeting->type_display }}</p>
                    </div>
                    <div>
                        @if($meeting->status === 'completed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                Completed
                            </span>
                        @elseif($meeting->status === 'ongoing')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                Ongoing
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                Scheduled
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase">Date</p>
                        <p class="text-sm font-medium text-gray-900">{{ $meeting->scheduled_at->format('M d, Y') }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase">Time</p>
                        <p class="text-sm font-medium text-gray-900">{{ $meeting->scheduled_at->format('h:i A') }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase">Duration</p>
                        <p class="text-sm font-medium text-gray-900">{{ $meeting->duration_minutes }} min</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 uppercase">Location</p>
                        <p class="text-sm font-medium text-gray-900">{{ $meeting->location }}</p>
                    </div>
                </div>

                @if($meeting->description)
                    <div class="mb-6">
                        <p class="text-xs text-gray-500 uppercase mb-1">Description</p>
                        <p class="text-sm text-gray-700">{{ $meeting->description }}</p>
                    </div>
                @endif

                <div class="flex items-center gap-4 pt-4 border-t">
                    @if($this->canStartSession())
                        <button
                            wire:click="startSession"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Start Session
                        </button>
                    @endif

                    @if($this->canEndSession())
                        <button
                            wire:click="endSession"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                            </svg>
                            End Session
                        </button>
                    @endif

                    <a
                        href="{{ route('admin.teaching-sessions') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
                    >
                        Back to Sessions
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Attendance Records</h3>

                @if($meeting->attendance_open && $meeting->status !== 'completed')
                    <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <span class="font-medium">Check-in Code:</span>
                            <span class="font-mono text-lg">{{ $meeting->meeting_code }}</span>
                        </p>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Checked In</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($this->attendees as $attendee)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <img class="h-8 w-8 rounded-full" src="{{ $attendee['avatar_url'] }}" alt="">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $attendee['name'] }}</div>
                                                <div class="text-xs text-gray-500">{{ $attendee['student_id'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($attendee['status'] === 'present')
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Present
                                            </span>
                                        @elseif($attendee['status'] === 'late')
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Late
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                                Absent
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        @if($attendee['checked_in_at'])
                                            {{ $attendee['checked_in_at']->format('h:i A') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        {{ $attendee['check_in_method'] ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                        No attendance records yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4">
            @can('mark attendance')
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Manual Attendance</h3>
                    @livewire('teaching.attendance-marker', ['session' => $meeting])
                </div>
            @endcan

            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Present</span>
                        <span class="text-sm font-medium text-green-600">{{ $meeting->getPresentCount() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Late</span>
                        <span class="text-sm font-medium text-yellow-600">{{ $meeting->getLateCount() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Absent</span>
                        <span class="text-sm font-medium text-red-600">{{ $meeting->getAbsentCount() }}</span>
                    </div>
                    <div class="pt-4 border-t">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">Total Checked In</span>
                            <span class="text-lg font-bold text-gray-900">{{ $meeting->getAttendanceCount() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($meeting->attendance_open && $meeting->status !== 'completed')
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">QR Code</h3>
                    <div class="flex flex-col items-center">
                        <div class="p-4 bg-white border-2 border-gray-100 rounded-lg mb-4">
                            {!! QrCode::format('svg')->size(200)->generate(route('attendance.verify', ['code' => $meeting->meeting_code])) !!}
                        </div>
                        <p class="text-xs text-gray-500 text-center">
                            Members can scan this QR code to check in<br>
                            Code expires in {{ $meeting->code_expires_minutes }} minutes
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
