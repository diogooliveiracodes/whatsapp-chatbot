<x-app-layout>
    <x-global.header>
        {{ __('schedules.messages.created') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <!-- Success Message -->
                    <div class="mb-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/20 mb-4">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            {{ __('schedules.messages.created') }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('schedules.messages.created_description') }}
                        </p>
                    </div>

                    <!-- Schedule Card -->
                    @if(isset($schedule))
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4 text-center">
                                {{ __('schedules.created_schedule_details') }}
                            </h4>
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-16 flex flex-col items-left justify-center">
                                            <span class="text-gray-900 dark:text-gray-100 font-bold leading-tight">
                                                {{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}
                                            </span>
                                            <span class="text-gray-500 dark:text-gray-400 text-xs">
                                                {{ \Carbon\Carbon::parse($schedule['schedule_date'])->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                                            {{ __('schedules.booked_slots') }}
                                        </span>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('schedules.edit', array_merge([$schedule['id']], request()->has('unit_id') ? ['unit_id' => request('unit_id')] : [])) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('schedules.destroy', $schedule['id']) }}" method="POST" class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300" onclick="return confirm('{{ __('schedules.messages.confirm_delete') }}')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Cliente</div>
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $schedule['customer']['name'] ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Serviço</div>
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $schedule['unit_service_type']['name'] ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</div>
                                            <div class="text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($schedule['status'] === 'confirmed') bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-700
                                                @elseif($schedule['status'] === 'pending') bg-yellow-50 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700
                                                @else bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-700 @endif">
                                                    {{ __('schedules.statuses.' . $schedule['status']) }}
                                                </span>
                                            </div>
                                        </div>
                                        @if ($schedule['notes'])
                                            <div class="sm:col-span-2 lg:col-span-3">
                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Observações</div>
                                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $schedule['notes'] }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <!-- Daily Schedule Button -->
                        <a href="{{ route('schedules.daily', request()->has('unit_id') ? ['unit_id' => request('unit_id')] : []) }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white dark:text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 dark:focus:bg-indigo-600 active:bg-indigo-800 dark:active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('schedules.view_daily') }}
                        </a>

                        <!-- Weekly Schedule Button -->
                        <a href="{{ route('schedules.weekly', request()->has('unit_id') ? ['unit_id' => request('unit_id')] : []) }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white dark:text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 dark:focus:bg-gray-600 active:bg-gray-800 dark:active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            {{ __('schedules.view_weekly') }}
                        </a>
                    </div>

                    <!-- Create Another Schedule Button -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('schedules.create', request()->has('unit_id') ? ['unit_id' => request('unit_id')] : []) }}"
                            class="inline-flex items-center hover:text-green-800 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('schedules.create_another') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
