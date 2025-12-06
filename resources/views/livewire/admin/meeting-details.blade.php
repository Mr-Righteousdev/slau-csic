
<div>

        <x-common.page-breadcrumb pageTitle="Meeting Details" />
        <div class="space-y-6" wire:poll.5s="refreshAttendance">
                    {{-- Flash Messages --}}
                    @if(session()->has('success'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                    @endif

                    @if(session()->has('error'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <p class="ml-3 text-sm text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Meeting Info Header --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h2 class="text-2xl font-bold text-gray-900">{{ $meeting->title }}</h2>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($meeting->status === 'ongoing') bg-green-100 text-green-800
                                        @elseif($meeting->status === 'scheduled') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($meeting->status) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>{{ $meeting->scheduled_at->format('l, F j, Y') }}</span>
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ $meeting->scheduled_at->format('g:i A') }} ({{ $meeting->duration_minutes }} min)</span>
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>{{ $meeting->location }}</span>
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        <span>{{ $meeting->type_display }}</span>
                                    </div>
                                </div>

                                @if($meeting->description)
                                <p class="mt-4 text-gray-700">{{ $meeting->description }}</p>
                                @endif

                                <div class="mt-4 text-sm">
                                    <span class="font-mono bg-gray-100 px-3 py-1 rounded">
                                        Meeting Code: <strong class="text-blue-600">{{ $meeting->meeting_code }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Control Panel --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Attendance Control</h3>

                        <div class="flex flex-wrap gap-3">
                            @if(!$meeting->attendance_open && !$meeting->hasEnded())
                                @can('open_attendance')
                                <button
                                    wire:click="openAttendance"
                                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Open Attendance
                                </button>
                                @endcan
                            @endif

                            @if($meeting->attendance_open)
                                <button
                                    wire:click="toggleQrCode"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                    {{ $showQrCode ? 'Hide QR Code' : 'Show QR Code' }}
                                </button>

                                @can('close_attendance')
                                <button
                                    wire:click="closeAttendance"
                                    wire:confirm="Are you sure you want to close attendance?"
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Close Attendance
                                </button>
                                @endcan
                            @endif

                            @can('record_attendance_manual')
                            <button
                                wire:click="openManualAttendanceModal"
                                class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Record Manually
                            </button>
                            @endcan
                        </div>

                        {{-- QR Code Display --}}
                        @if($showQrCode && $meeting->attendance_open)
                        <div class="mt-6 p-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <div class="text-center">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Scan QR Code to Check In</h4>
                                <div class="inline-block p-4 bg-white rounded-lg shadow-sm">
                                    {!! QrCode::size(250)->generate($meeting->getQrCodeUrl()) !!}
                                </div>
                                <p class="mt-4 text-sm text-gray-600">Meeting Code: <span class="font-mono font-bold text-blue-600">{{ $meeting->meeting_code }}</span></p>
                                <p class="mt-2 text-xs text-gray-500">Members can scan this code with the mobile app</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Attendance Stats --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Total Attendance</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $attendees->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        @if($meeting->expected_attendees > 0)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Expected</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $meeting->expected_attendees }}</p>
                                </div>
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Attendance Rate</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $meeting->getAttendanceRate() }}%</p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Status</p>
                                    <p class="text-lg font-semibold mt-1
                                        @if($meeting->attendance_open) text-green-600
                                        @else text-gray-600
                                        @endif">
                                        {{ $meeting->attendance_open ? 'Open' : 'Closed' }}
                                    </p>
                                </div>
                                <div class="w-12 h-12
                                    @if($meeting->attendance_open) bg-green-100
                                    @else bg-gray-100
                                    @endif
                                    rounded-full flex items-center justify-center">
                                    @if($meeting->attendance_open)
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    @else
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Attendees List --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Attendees ({{ $attendees->count() }})
                                @if($meeting->attendance_open)
                                <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full animate-pulse">
                                    Live
                                </span>
                                @endif
                            </h3>
                        </div>

                        @if($attendees->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($attendees as $attendance)
                            <div class="px-6 py-4 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 font-semibold text-sm">
                                                {{ substr($attendance->user->name, 0, 2) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $attendance->user->student_id }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <p class="text-sm text-gray-900">{{ $attendance->checked_in_at->format('g:i A') }}</p>
                                            <p class="text-xs text-gray-500">{{ $attendance->check_in_method_display }}</p>
                                        </div>
                                        @can('edit_attendance')
                                        <button
                                            wire:click="removeAttendance({{ $attendance->id }})"
                                            wire:confirm="Remove this attendance record?"
                                            class="text-red-600 hover:text-red-800"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p>No attendance records yet</p>
                            <p class="text-sm mt-1">Members will appear here after checking in</p>
                        </div>
                        @endif
                    </div>

                    {{-- Manual Attendance Modal --}}
                    @if($showManualAttendanceModal)
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
                        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Record Manual Attendance</h3>
                                <button wire:click="closeManualAttendanceModal" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="p-6 overflow-y-auto max-h-96">
                                <div class="mb-4">
                                    <input
                                        type="text"
                                        wire:model.live.debounce.300ms="searchTerm"
                                        placeholder="Search by name, email, or student ID..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                </div>

                                <div class="space-y-2">
                                    @forelse($availableMembers as $member)
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            wire:model="selectedUsers"
                                            value="{{ $member->id }}"
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        >
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $member->student_id }} • {{ $member->email }}</p>
                                        </div>
                                    </label>
                                    @empty
                                    <p class="text-center text-gray-500 py-4">No members found</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                                <p class="text-sm text-gray-600">{{ count($selectedUsers) }} member(s) selected</p>
                                <div class="flex space-x-3">
                                    <button
                                        wire:click="closeManualAttendanceModal"
                                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        wire:click="recordManualAttendance"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                                        @if(count($selectedUsers) === 0) disabled @endif
                                    >
                                        Record Attendance
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

</div>

