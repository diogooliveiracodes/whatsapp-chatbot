<x-app-layout>
    <x-global.header>
        {{ __('pages.schedules') }}
    </x-global.header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <div class="flex gap-4 mb-4 justify-between">
                        <x-global.create-button :route="route('schedules.create')" :text="__('schedules.create')" />
                        <div>
                            <a href="{{ route('schedules.index', ['date' => $startOfWeek->copy()->subDays(7)->format('Y-m-d')]) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('schedules.previous_week') }}
                            </a>
                            <a href="{{ route('schedules.index', ['date' => now()->format('Y-m-d')]) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('schedules.today') }}
                            </a>
                            <a href="{{ route('schedules.index', ['date' => $startOfWeek->copy()->addDays(7)->format('Y-m-d')]) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('schedules.next_week') }}
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th
                                                class="w-24 px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700">
                                                {{ __('schedules.start_time') }}
                                            </th>

                                            @foreach ($days as $dayKey => $dayName)
                                                @php
                                                    $isEnabled = $unitSettings->$dayKey;
                                                    $startTime = $unitSettings->{$dayKey . '_start'};
                                                    $endTime = $unitSettings->{$dayKey . '_end'};
                                                @endphp
                                                <th
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700 {{ !$isEnabled ? 'opacity-50' : '' }} {{ $startOfWeek->copy()->addDays(array_search($dayKey, array_keys($days)))->isToday() ? 'border-t-2 border-l-2 border-r-2 border-red-500' : '' }}">
                                                    <div class="font-semibold">{{ $dayName }}</div>
                                                    <div class="text-xs text-gray-400">
                                                        {{ $startOfWeek->copy()->addDays(array_search($dayKey, array_keys($days)))->format('d/m') }}
                                                    </div>
                                                    @if (!$isEnabled)
                                                        <div class="text-xs text-red-500">
                                                            {{ __('schedules.messages.closed') }}</div>
                                                    @else
                                                        <div class="text-xs text-green-600">
                                                            {{ $startTime ? \Carbon\Carbon::parse($startTime)->format('H:i') : '--:--' }}
                                                            -
                                                            {{ $endTime ? \Carbon\Carbon::parse($endTime)->format('H:i') : '--:--' }}
                                                        </div>
                                                    @endif
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @php
                                            $interval = $unitSettings->appointment_duration_minutes;
                                            $startTime = $workingHours['startTime'];
                                            $endTime = $workingHours['endTime'];
                                        @endphp

                                        @for ($time = $startTime->copy(); $time->lt($endTime); $time->addMinutes($interval))
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td
                                                    class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                                                    {{ $time->format('H:i') }}
                                                </td>
                                                @foreach ($days as $dayKey => $dayName)
                                                    @php
                                                        $dayIndex = array_search($dayKey, array_keys($days));
                                                        $currentDate = $startOfWeek
                                                            ->copy()
                                                            ->addDays($dayIndex)
                                                            ->format('Y-m-d');
                                                        $currentTime = $time->format('H:i');
                                                        $currentEndTime = $time
                                                            ->copy()
                                                            ->addMinutes($interval)
                                                            ->format('H:i');
                                                        $schedule = $scheduleService->getScheduleForTimeSlot(
                                                            $schedules,
                                                            $currentDate,
                                                            $currentTime,
                                                            $currentEndTime,
                                                        );
                                                        // if($schedule){

                                                        //     dd($schedule);
                                                        // }

                                                        $isWithinOperatingHours = $scheduleService->isWithinOperatingHours(
                                                            $time,
                                                            $dayKey,
                                                            $unitSettings,
                                                        );
                                                    @endphp
                                                    <td
                                                        class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-r border-gray-600 relative group
                                                        {{ !$isWithinOperatingHours ? 'bg-gray-100 dark:bg-gray-700 opacity-50' : '' }}
                                                        {{ $startOfWeek->copy()->addDays(array_search($dayKey, array_keys($days)))->isToday() && $isWithinOperatingHours ? 'border-l-2 border-r-2 border-red-500' : '' }}
                                                        {{ $startOfWeek->copy()->addDays(array_search($dayKey, array_keys($days)))->isToday() && $isWithinOperatingHours && $time->copy()->addMinutes($interval)->gte($endTime) ? 'border-b-2 border-red-500' : '' }}">
                                                        @if ($isWithinOperatingHours && $schedule)
                                                            <x-schedules.schedule-card :schedule="$schedule" />
                                                        @elseif ($isWithinOperatingHours)
                                                            <a href="{{ route('schedules.create', [
                                                                'schedule_date' => $currentDate,
                                                                'start_time' => $currentTime,
                                                            ]) }}"
                                                                class="absolute inset-0 flex items-center justify-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600">
                                                                <div
                                                                    class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke-width="1.5" stroke="currentColor"
                                                                        class="w-6 h-6 text-gray-500 dark:text-gray-400">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                                    </svg>
                                                                </div>
                                                            </a>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
