<x-app-layout>
    <x-header>
        {{ __('schedules.calendar') }}
    </x-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('error'))
                        <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <a href="{{ route('schedules.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('schedules.create') }}
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="w-24 px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700">
                                                {{ __('schedules.start_time') }}
                                            </th>
                                            @php
                                                $days = [
                                                    'sunday' => __('unitSettings.sunday'),
                                                    'monday' => __('unitSettings.monday'),
                                                    'tuesday' => __('unitSettings.tuesday'),
                                                    'wednesday' => __('unitSettings.wednesday'),
                                                    'thursday' => __('unitSettings.thursday'),
                                                    'friday' => __('unitSettings.friday'),
                                                    'saturday' => __('unitSettings.saturday')
                                                ];
                                                $currentDate = now();
                                                $startOfWeek = $currentDate->startOfWeek();
                                            @endphp
                                            @foreach($days as $dayKey => $dayName)
                                                @php
                                                    $isEnabled = $unitSettings->$dayKey;
                                                    $startTime = $unitSettings->{$dayKey . '_start'};
                                                    $endTime = $unitSettings->{$dayKey . '_end'};
                                                @endphp
                                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700 {{ !$isEnabled ? 'opacity-50' : '' }}">
                                                    <div class="font-semibold">{{ $dayName }}</div>
                                                    <div class="text-xs text-gray-400">{{ $startOfWeek->copy()->addDays(array_search($dayKey, array_keys($days)))->format('d/m') }}</div>
                                                    @if(!$isEnabled)
                                                        <div class="text-xs text-red-500">{{ __('schedules.messages.closed') }}</div>
                                                    @else
                                                        <div class="text-xs text-green-600">
                                                            {{ $startTime ? \Carbon\Carbon::parse($startTime)->format('H:i') : '--:--' }} - {{ $endTime ? \Carbon\Carbon::parse($endTime)->format('H:i') : '--:--' }}
                                                        </div>
                                                    @endif
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @php
                                            $interval = 30; // 30 minutes
                                            $allStartTimes = collect($days)->map(function($dayName, $dayKey) use ($unitSettings) {
                                                return $unitSettings->{$dayKey . '_start'};
                                            })->filter()->map(function($time) {
                                                return \Carbon\Carbon::parse($time);
                                            })->min();
                                            $allEndTimes = collect($days)->map(function($dayName, $dayKey) use ($unitSettings) {
                                                return $unitSettings->{$dayKey . '_end'};
                                            })->filter()->map(function($time) {
                                                return \Carbon\Carbon::parse($time);
                                            })->max();

                                            $startTime = $allStartTimes ? $allStartTimes : \Carbon\Carbon::createFromTime(8, 0, 0);
                                            $endTime = $allEndTimes ? $allEndTimes : \Carbon\Carbon::createFromTime(18, 0, 0);
                                        @endphp

                                        @for($time = $startTime->copy(); $time->lt($endTime); $time->addMinutes($interval))
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700">
                                                    {{ $time->format('H:i') }}
                                                </td>
                                                @foreach($days as $dayKey => $dayName)
                                                    @php
                                                        $dayIndex = array_search($dayKey, array_keys($days));
                                                        $currentDate = $startOfWeek->copy()->addDays($dayIndex)->format('Y-m-d');
                                                        $currentTime = $time->format('H:i');
                                                        $schedule = $schedules->where('date', $currentDate)
                                                                            ->where('time', $currentTime)
                                                                            ->first();
                                                        $isDayEnabled = $unitSettings->$dayKey;
                                                        $dayStartTime = $unitSettings->{$dayKey . '_start'};
                                                        $dayEndTime = $unitSettings->{$dayKey . '_end'};
                                                        $isWithinOperatingHours = $isDayEnabled &&
                                                            (!$dayStartTime || $time->gte(\Carbon\Carbon::parse($dayStartTime))) &&
                                                            (!$dayEndTime || $time->lt(\Carbon\Carbon::parse($dayEndTime)));
                                                    @endphp
                                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 {{ !$isWithinOperatingHours ? 'bg-gray-100 dark:bg-gray-700 opacity-50' : '' }}">
                                                        @if($isWithinOperatingHours && $schedule)
                                                            <div class="bg-blue-50 dark:bg-blue-900 p-2 rounded-md">
                                                                <p class="text-sm text-blue-800 dark:text-blue-200">{{ $schedule->title }}</p>
                                                            </div>
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
