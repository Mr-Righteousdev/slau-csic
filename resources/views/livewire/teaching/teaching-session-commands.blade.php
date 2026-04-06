<div>
    <x-common.page-breadcrumb pageTitle="Teaching Session Commands" />

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

    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 lg:col-span-6">
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Manual Commands</h3>
                
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-900">Auto-Status Check</h4>
                                <p class="text-sm text-gray-500">Check and auto-start/end sessions based on time</p>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                        {{ $this->getScheduledSessionsCount() }} ready to start
                                    </span>
                                    <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded">
                                        {{ $this->getOngoingSessionsCount() }} ongoing
                                    </span>
                                </div>
                            </div>
                            <button
                                wire:click="runAutoStatus"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove wire:target="runAutoStatus">Run Now</span>
                                <span wire:loading wire:target="runAutoStatus">Running...</span>
                            </button>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-900">Recalculate All Scores</h4>
                                <p class="text-sm text-gray-500">Recalculate scores for all members based on attendance</p>
                            </div>
                            <button
                                wire:click="recalculateScores"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove wire:target="recalculateScores">Run Now</span>
                                <span wire:loading wire:target="recalculateScores">Running...</span>
                            </button>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-900">Finalize Absences</h4>
                                <p class="text-sm text-gray-500">Mark all non-checked-in members as absent for ongoing sessions</p>
                            </div>
                            <button
                                wire:click="finalizeAbsences"
                                class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove wire:target="finalizeAbsences">Run Now</span>
                                <span wire:loading wire:target="finalizeAbsences">Running...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-6">
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Command Output</h3>
                
                @if($output)
                    <div class="bg-gray-900 rounded-lg p-4 text-green-400 font-mono text-sm overflow-auto max-h-64">
                        <pre>{{ $output }}</pre>
                    </div>
                @else
                    <div class="bg-gray-50 rounded-lg p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p>Run a command to see output here</p>
                    </div>
                @endif
            </div>

            <div class="bg-blue-50 rounded-lg border border-blue-200 p-4 mt-4">
                <h4 class="font-medium text-blue-900 mb-2">Info</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• These commands also run automatically via scheduler</li>
                    <li>• Auto-status runs every minute</li>
                    <li>• Use manual buttons for immediate action</li>
                </ul>
            </div>
        </div>
    </div>
</div>