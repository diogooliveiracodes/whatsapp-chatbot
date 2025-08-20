<x-guest-layout>
    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Success Message -->
                    <div class="mb-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/20 mb-4">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-semibold text-white mb-2">{{ __('schedule_link.success_title') }}</h1>
                        <p class="text-gray-300 mb-6">{{ __('schedule_link.success_message') }}</p>
                    </div>

                    <!-- Schedule Card -->
                    @if(isset($schedule))
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-white mb-4 text-center">
                                {{ __('schedules.created_schedule_details') }}
                            </h4>
                            <div class="border border-gray-700 rounded-lg overflow-hidden">
                                <div class="flex items-center justify-between p-4 bg-gray-700">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-16 flex flex-col items-left justify-center">
                                            <span class="text-white font-bold leading-tight">
                                                {{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}
                                            </span>
                                            <span class="text-gray-400 text-xs">
                                                {{ \Carbon\Carbon::parse($schedule['schedule_date'])->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900/30 text-blue-300 border border-blue-700">
                                            {{ __('schedules.booked_slots') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-4 bg-gray-800 border-t border-gray-700">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-400">Cliente</div>
                                            <div class="text-sm text-white">{{ $schedule['customer']['name'] ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-400">Serviço</div>
                                            <div class="text-sm text-white">{{ $schedule['unit_service_type']['name'] ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-400">Unidade</div>
                                            <div class="text-sm text-white">{{ $schedule['unit']['name'] ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-400">Status</div>
                                            <div class="text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($schedule['status'] === 'confirmed') bg-green-900/30 text-green-300 border border-green-700
                                                @elseif($schedule['status'] === 'pending') bg-yellow-900/30 text-yellow-300 border border-yellow-700
                                                @else bg-red-900/30 text-red-300 border border-red-700 @endif">
                                                    {{ __('schedules.statuses.' . $schedule['status']) }}
                                                </span>
                                            </div>
                                        </div>
                                        @if ($schedule['notes'])
                                            <div class="sm:col-span-2 lg:col-span-3">
                                                <div class="text-sm font-medium text-gray-400">Observações</div>
                                                <div class="text-sm text-white">{{ $schedule['notes'] }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Button -->
                    <div class="text-center">
                        <a href="{{ route('schedule-link.show', ['company' => $company, 'unit' => $unit->id]) }}"
                           class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('schedule_link.book_another') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>


