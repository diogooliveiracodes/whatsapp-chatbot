<x-global.header>
    {{ __('pages.semanal_schedules') }}
</x-global.header>

<div class="py-6 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                <x-global.session-alerts />

                <!-- Unit selector for owners -->
                @if($showUnitSelector)
                    <div class="mb-6">
                        <label for="unit-selector" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('schedules.unit_selection') }}
                        </label>
                        <select id="unit-selector"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                                onchange="changeUnit(this.value)">
                            @foreach($units as $unitOption)
                                <option value="{{ $unitOption->id }}" {{ $unit->id == $unitOption->id ? 'selected' : '' }}>
                                    {{ $unitOption->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Header with navigation -->
                <div class="flex flex-col gap-4 mb-6">

                    <!-- Week navigation -->
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto sm:mx-auto">
                        <div class="flex items-center justify-between sm:justify-start gap-2">
                            <a href="{{ route('schedules.weekly', array_merge(['date' => $startOfWeek->copy()->subDays(7)->format('Y-m-d')], request()->has('unit_id') ? ['unit_id' => request('unit_id')] : [])) }}"
                                class="inline-flex items-center px-3 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                <span class="hidden sm:inline ml-1">{{ __('schedules.previous_week') }}</span>
                            </a>

                            <div class="text-center px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-md">
                                <div class="font-semibold text-sm text-gray-900 dark:text-gray-100">
                                    {{ $startOfWeek->format('d/m') }} -
                                    {{ $startOfWeek->copy()->addDays(6)->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">{{ __('schedules.week_view') }}
                                </div>
                            </div>

                            <a href="{{ route('schedules.weekly', array_merge(['date' => $startOfWeek->copy()->addDays(7)->format('Y-m-d')], request()->has('unit_id') ? ['unit_id' => request('unit_id')] : [])) }}"
                                class="inline-flex items-center px-3 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <span class="hidden sm:inline mr-1">{{ __('schedules.next_week') }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>

                        <a href="{{ route('schedules.weekly', array_merge(['date' => now()->format('Y-m-d')], request()->has('unit_id') ? ['unit_id' => request('unit_id')] : [])) }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white dark:text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 dark:focus:bg-indigo-600 active:bg-indigo-800 dark:active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('schedules.current_week') }}
                        </a>
                    </div>
                </div>

                <!-- Weekly schedule content -->
                <div class="space-y-8">
                    @foreach ($days as $dayKey => $dayName)
                        @php
                            $isEnabled = $unitSettings->$dayKey;
                            $startTime = $unitSettings->{$dayKey . '_start'};
                            $endTime = $unitSettings->{$dayKey . '_end'};
                            $dayIndex = array_search($dayKey, array_keys($days));
                            $currentDate = $startOfWeek->copy()->addDays($dayIndex)->format('Y-m-d');
                            $isToday = $startOfWeek->copy()->addDays($dayIndex)->isToday();
                            $userTimezone = auth()->user()->unit->unitSettings->timezone ?? 'UTC';
                            $startDisplay = $startTime
                                ? \Carbon\Carbon::parse($currentDate . ' ' . $startTime, 'UTC')
                                    ->setTimezone($userTimezone)
                                    ->format('H:i')
                                : '--:--';
                            $endDisplay = $endTime
                                ? \Carbon\Carbon::parse($currentDate . ' ' . $endTime, 'UTC')
                                    ->setTimezone($userTimezone)
                                    ->format('H:i')
                                : '--:--';
                        @endphp

                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden {{ $isToday ? 'ring-2 ring-red-500' : '' }}">
                            <!-- Day header -->
                            <div
                                class="bg-blue-50 dark:bg-blue-900/20 px-4 py-3 border-b border-blue-200 dark:border-blue-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                                <span
                                                    class="text-sm font-semibold text-indigo-600 dark:text-indigo-300">
                                                    {{ $startOfWeek->copy()->addDays($dayIndex)->format('d') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $dayName }}
                                            </h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $startOfWeek->copy()->addDays($dayIndex)->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if (!$isEnabled)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-700">
                                                {{ __('schedules.messages.closed') }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-700">
                                                {{ $startDisplay }} - {{ $endDisplay }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Day content -->
                            @if ($isEnabled)
                                @php
                                    $interval = $unitSettings->appointment_duration_minutes;
                                    $dayWorkingHours = $scheduleService->calculateWorkingHoursForDay(
                                        $dayKey,
                                        $unitSettings,
                                    );
                                    $startTime = $dayWorkingHours['startTime'];
                                    $endTime = $dayWorkingHours['endTime'];
                                @endphp

                                @if ($startTime && $endTime)
                                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @php
                                            $break = $scheduleService->getBreakForDay($dayKey, \Carbon\Carbon::parse($currentDate), $unitSettings);
                                        @endphp
                                        @if ($break['startTime'] && $break['endTime'])
                                            <div class="flex items-center justify-between p-2 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 rounded mb-2">
                                                <span class="text-xs font-medium text-yellow-800 dark:text-yellow-200">{{ __('schedules.break') }}</span>
                                                <span class="text-xs font-mono text-yellow-700 dark:text-yellow-300">{{ $break['startTime']->format('H:i') }} - {{ $break['endTime']->format('H:i') }}</span>
                                            </div>
                                        @endif
                                        @for ($time = $startTime->copy(); $time->lt($endTime); $time->addMinutes($interval))
                                            @php
                                                $currentTime = $time->format('H:i');
                                                $currentEndTime = $time->copy()->addMinutes($interval)->format('H:i');
                                                $schedule = $scheduleService->getScheduleForTimeSlot(
                                                    $schedules,
                                                    $currentDate,
                                                    $currentTime,
                                                    $currentEndTime,
                                                );

                                                $block = $scheduleBlockService->getBlockForTimeSlot(
                                                    $blocks,
                                                    $currentDate,
                                                    $currentTime,
                                                    $currentEndTime,
                                                );

                                                $isWithinOperatingHours = $scheduleService->isWithinOperatingHours(
                                                    $time,
                                                    $dayKey,
                                                    $unitSettings,
                                                );

                                                // Validação de horário passado
                                                $userTimezone = auth()->user()->unit->unitSettings->timezone ?? 'UTC';
                                                $currentTimeInUserTimezone = now()->setTimezone($userTimezone);
                                                $slotEndDateTime = \Carbon\Carbon::parse(
                                                    $currentDate . ' ' . $currentEndTime,
                                                    $userTimezone,
                                                );
                                                $isPastSlot = $currentTimeInUserTimezone->gt($slotEndDateTime);
                                            @endphp

                                            @php
                                                $isBreakSlot = $break['startTime'] && $break['endTime'] && $time->gte($break['startTime']) && $time->lt($break['endTime']);
                                            @endphp

                                            @if ($isBreakSlot)
                                                <div class="border border-yellow-200 dark:border-yellow-700 rounded-lg overflow-hidden mt-2">
                                                    <div class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900/30">
                                                        <div class="flex items-center space-x-3">
                                                            <div class="h-16 flex flex-col items-left justify-center">
                                                                <span class="text-yellow-800 dark:text-yellow-200 font-bold leading-tight">{{ $currentTime }} - {{ $currentEndTime }}</span>
                                                                <span class="text-yellow-700 dark:text-yellow-300 text-xs">{{ __('schedules.break') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($isWithinOperatingHours)
                                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mt-2">
                                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700">
                                                        <div class="flex items-center space-x-3">
                                                            <div class="h-16 flex flex-col items-left justify-center">
                                                                <span
                                                                    class="text-gray-900 dark:text-gray-100 font-bold leading-tight">{{ $currentTime }}
                                                                    - {{ $currentEndTime }}</span>
                                                                <span
                                                                    class="text-gray-500 dark:text-gray-400 text-xs">{{ $interval }}
                                                                    min</span>
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center space-x-2">
                                                            @if ($schedule)
                                                                @php
                                                                    $userTimezone =
                                                                        auth()->user()->unit->unitSettings->timezone ??
                                                                        'UTC';
                                                                    $scheduleEndDateTime = \Carbon\Carbon::parse(
                                                                        $schedule['end'],
                                                                        $userTimezone,
                                                                    );
                                                                    $currentTimeInUserTimezone = now()->setTimezone(
                                                                        $userTimezone,
                                                                    );
                                                                    $isPastSchedule = $currentTimeInUserTimezone->gt(
                                                                        $scheduleEndDateTime,
                                                                    );
                                                                @endphp
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                                                                    {{ __('schedules.booked_slots') }}
                                                                </span>
                                                                                                                                 @if (!$isPastSchedule)
                                                                     <div class="flex space-x-2">
                                                                         <a href="{{ route('schedules.edit', array_merge([$schedule['id']], auth()->user()->isOwner() && isset($schedule['unit']['id']) ? ['unit_id' => $schedule['unit']['id']] : [])) }}"
                                                                             class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                                             <svg class="w-5 h-5" fill="none"
                                                                                 stroke="currentColor"
                                                                                 viewBox="0 0 24 24">
                                                                                 <path stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     stroke-width="2"
                                                                                     d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                             </svg>
                                                                         </a>
                                                                         <x-automated_messages.message-button :schedule="$schedule" :unit="$unit" />
                                                                         <form
                                                                             action="{{ route('schedules.destroy', $schedule['id']) }}"
                                                                             method="POST" class="inline delete-form">
                                                                             @csrf
                                                                             @method('DELETE')
                                                                             <button type="submit"
                                                                                 class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                                                                 onclick="return confirm('{{ __('schedules.messages.confirm_delete') }}')">
                                                                                 <svg class="w-5 h-5" fill="none"
                                                                                     stroke="currentColor"
                                                                                     viewBox="0 0 24 24">
                                                                                     <path stroke-linecap="round"
                                                                                         stroke-linejoin="round"
                                                                                         stroke-width="2"
                                                                                         d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                 </svg>
                                                                             </button>
                                                                         </form>
                                                                     </div>
                                                                 @endif
                                                            @elseif ($block)
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-700">
                                                                    {{ __('schedules.blocked_slots') }}
                                                                </span>
                                                            @else
                                                                @if ($isPastSlot)
                                                                    <span
                                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 border border-purple-200 dark:border-purple-700">
                                                                        {{ __('schedules.time_passed') }}
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-700">
                                                                        {{ __('schedules.available_slots') }}
                                                                    </span>
                                                                    <a href="{{ route('schedules.create', array_merge([
                                                                        'schedule_date' => $currentDate,
                                                                        'start_time' => $currentTime,
                                                                    ], auth()->user()->isOwner() ? ['unit_id' => $unit->id] : [])) }}"
                                                                        class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                                                                        <svg class="w-5 h-5" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                                        </svg>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>

                                                    @if ($schedule)
                                                        <div
                                                            class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                                                <div>
                                                                    <div
                                                                        class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                                        Cliente</div>
                                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                                        {{ $schedule['customer']['name'] ?? 'N/A' }}</div>
                                                                </div>
                                                                <div>
                                                                    <div
                                                                        class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                                        Serviço</div>
                                                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                                                        {{ $schedule['unit_service_type']['name'] ?? 'N/A' }}</div>
                                                                </div>
                                                                <div>
                                                                    <div
                                                                        class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                                        Status</div>
                                                                    <div class="text-sm">
                                                                        <span
                                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                        @if ($schedule['status'] === 'confirmed') bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-700
                                                                        @elseif($schedule['status'] === 'pending') bg-yellow-50 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700
                                                                        @else bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-700 @endif">
                                                                            {{ __('schedules.statuses.' . $schedule['status']) }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                @if ($schedule['notes'])
                                                                    <div class="sm:col-span-2 lg:col-span-3">
                                                                        <div
                                                                            class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                                            Observações</div>
                                                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                                                            {{ $schedule['notes'] }}</div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @elseif ($block)
                                                        <div
                                                            class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                                                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                                                <strong>Motivo:</strong>
                                                                {{ $block->reason ?? 'Horário bloqueado' }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endfor
                                    </div>
                                @else
                                    <div class="p-6 text-center">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Horários não configurados
                                        </h3>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            Este dia não possui horários de funcionamento configurados.
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="p-6 text-center">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('schedules.messages.closed') }}
                                    </h3>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Este dia não está disponível para agendamentos.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Função para inicializar os formulários de exclusão
        function initializeDeleteForms() {
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                // Remove event listeners existentes para evitar duplicação
                form.removeEventListener('submit', handleDeleteSubmit);
                form.addEventListener('submit', handleDeleteSubmit);
            });
        }

        // Função para lidar com o submit do formulário de exclusão
        function handleDeleteSubmit(e) {
            e.preventDefault();
            e.stopPropagation();

            if (confirm('{{ __('schedules.messages.confirm_delete') }}')) {
                // Adiciona um indicador visual de carregamento
                const button = e.target.querySelector('button[type="submit"]');
                if (button) {
                    button.disabled = true;
                    button.innerHTML =
                        '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                }

                // Submete o formulário
                this.submit();
            }
        }

        // Inicializa quando o DOM estiver pronto
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeDeleteForms);
        } else {
            initializeDeleteForms();
        }

        // Re-inicializa após mudanças dinâmicas no DOM (se necessário)
        document.addEventListener('DOMContentLoaded', function() {
            // Observa mudanças no DOM para re-inicializar se necessário
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        initializeDeleteForms();
                    }
                });
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });

        // Função para mudar a unidade selecionada
        function changeUnit(unitId) {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('unit_id', unitId);
            window.location.href = currentUrl.toString();
        }
    </script>

    <x-scroll-to-top />
@endpush
