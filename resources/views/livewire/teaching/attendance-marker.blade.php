<div>
    <h3 class="text-lg font-medium text-gray-900 mb-4">Manual Attendance</h3>

    <div class="space-y-4">
        <div class="flex items-end gap-4">
            <div class="flex-1">
                <label for="selectedUserId" class="block text-sm font-medium text-gray-700 mb-1">
                    Select Member
                </label>
                <select
                    id="selectedUserId"
                    wire:model="selectedUserId"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">Select a member...</option>
                    @foreach($unmarkedMembers as $member)
                        <option value="{{ $member['id'] }}">{{ $member['name'] }} ({{ $member['student_id'] }})</option>
                    @endforeach
                </select>
            </div>

            <div class="w-32">
                <label for="selectedStatus" class="block text-sm font-medium text-gray-700 mb-1">
                    Status
                </label>
                <select
                    id="selectedStatus"
                    wire:model="selectedStatus"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="present">Present</option>
                    <option value="late">Late</option>
                    <option value="absent">Absent</option>
                </select>
            </div>

            <button
                wire:click="markAttendance"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
            >
                Mark
            </button>
        </div>
    </div>

    <div class="mt-6">
        <h4 class="text-sm font-medium text-gray-700 mb-3">Current Attendance</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($attendees as $attendee)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <img class="h-6 w-6 rounded-full" src="{{ $attendee['avatar_url'] }}" alt="">
                                    <span class="text-sm text-gray-900">{{ $attendee['name'] }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-2">
                                @if($attendee['status'] === 'present')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Present
                                    </span>
                                @elseif($attendee['status'] === 'late')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Late
                                    </span>
                                @elseif($attendee['status'] === 'absent')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                        Absent
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-sm text-gray-500">
                                @if($attendee['checked_in_at'])
                                    {{ $attendee['checked_in_at']->format('h:i A') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-1">
                                    <button
                                        wire:click="updateStatus('{{ $attendee['id'] }}', 'present')"
                                        class="text-xs text-green-600 hover:text-green-800"
                                    >
                                        Present
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <button
                                        wire:click="updateStatus('{{ $attendee['id'] }}', 'late')"
                                        class="text-xs text-yellow-600 hover:text-yellow-800"
                                    >
                                        Late
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <button
                                        wire:click="updateStatus('{{ $attendee['id'] }}', 'absent')"
                                        class="text-xs text-red-600 hover:text-red-800"
                                    >
                                        Absent
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-4 text-center text-sm text-gray-500">
                                No attendance records yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>