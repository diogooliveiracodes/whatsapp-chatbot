<x-guest-layout>
    <div class="py-2 sm:py-4 pb-12">
        <div class="max-w-4xl mx-auto px-4 pb-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-1">
                    @php
                        $firstSchedule = $schedules->first();
                        $companyId = $firstSchedule?->unit?->company_id;
                        $unitId = $firstSchedule?->unit_id;
                    @endphp
                    @if (!empty($companyId) && !empty($unitId))
                        <div class="text-center mb-4">
                            <a href="{{ route('schedule-link.show', ['company' => $companyId, 'unit' => $unitId]) }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('schedule_link.book_another') }}
                            </a>
                        </div>
                    @endif

                    <div class="mb-6 text-center">
                        <h1 id="pageTitle" class="text-2xl font-semibold text-white mb-2">
                            {{ __('customer_schedules.title') }}</h1>
                    </div>

                    @if ($schedules->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-100">{{ __('customer_schedules.empty') }}</h3>
                            <p class="mt-1 text-sm text-gray-400">{{ __('customer_schedules.empty_description') }}</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($schedules as $schedule)
                                @php
                                    $tz = $schedule->unit->unitSettings->timezone ?? 'UTC';
                                    $startUtc = \Carbon\Carbon::parse(
                                        $schedule->schedule_date?->format('Y-m-d') .
                                            ' ' .
                                            ($schedule->start_time ?? ''),
                                        'UTC',
                                    );
                                    $endUtc = \Carbon\Carbon::parse(
                                        $schedule->schedule_date?->format('Y-m-d') . ' ' . ($schedule->end_time ?? ''),
                                        'UTC',
                                    );
                                    $startLocal = $startUtc->copy()->setTimezone($tz);
                                    $endLocal = $endUtc->copy()->setTimezone($tz);
                                @endphp
                                <div class="border border-gray-700 rounded-lg overflow-hidden">
                                    <div class="flex items-center justify-between p-4 bg-gray-700">
                                        <div class="flex items-center space-x-3">
                                            <div class="h-16 flex flex-col items-left justify-center">
                                                <span
                                                    class="text-white font-bold leading-tight">{{ $startLocal->format('H:i') }}
                                                    - {{ $endLocal->format('H:i') }}</span>
                                                <span
                                                    class="text-gray-300 text-xs">{{ $startLocal->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($schedule->status === 'confirmed') bg-green-900/30 text-green-300 border border-green-700
                                                @elseif($schedule->status === 'pending') bg-yellow-900/30 text-yellow-300 border border-yellow-700
                                                @else bg-red-900/30 text-red-300 border border-red-700 @endif">
                                                {{ __('schedules.statuses.' . $schedule->status) }}
                                            </span>
                                            <a href="{{ route('schedule-link.success', ['company' => $schedule->unit->company_id, 'unit' => $schedule->unit_id, 'uuid' => $schedule->uuid]) }}"
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                {{ __('actions.view') }}
                                            </a>

                                        </div>
                                    </div>
                                    <div class="p-4 bg-gray-800 border-t border-gray-700">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <div class="text-sm font-medium text-gray-400">
                                                    {{ __('customers.client') }}</div>
                                                <div class="text-sm text-white">
                                                    {{ $schedule->customer->name ?? 'N/A' }}</div>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-400">
                                                    {{ __('schedules.service_type') }}</div>
                                                <div class="text-sm text-white">
                                                    {{ $schedule->unitServiceType->name ?? 'N/A' }}</div>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-400">
                                                    {{ __('schedule_link.professional_label') }}</div>
                                                <div class="text-sm text-white">{{ $schedule->unit->name ?? 'N/A' }}
                                                </div>
                                            </div>
                                            @if ($schedule->notes)
                                                <div class="sm:col-span-2">
                                                    <div class="text-sm font-medium text-gray-400">
                                                        {{ __('schedules.notes') }}</div>
                                                    <div class="text-sm text-white">{{ $schedule->notes }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-400">
                                Página {{ $schedules->currentPage() }} de {{ $schedules->lastPage() }}
                            </div>
                            <div>
                                {{ $schedules->withQueryString()->onEachSide(1)->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
