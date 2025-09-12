<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $companyName ?? __('schedule_link.page_title_default') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Prevent automatic scrolling on page load */
        html {
            scroll-behavior: auto;
        }

        /* Only enable smooth scrolling for user interactions */
        html.user-interaction {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center bg-gray-100 dark:bg-gray-900">
        <x-global.session-alerts />

        @if ($hasMultipleUnits)
            <!-- Floating Back Button for Mobile -->
            <div class="sticky top-[calc(env(safe-area-inset-top,0px)+1rem)] z-50 sm:hidden w-full">
                <a href="{{ route('schedule-link.index', ['company' => $company]) }}"
                    class="ml-4 flex items-center justify-center w-12 h-12 bg-gray-800/80 backdrop-blur-sm border border-gray-600/50 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-2 focus:ring-offset-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
            </div>
        @endif

        <div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 py-4 sm:py-8 px-3 sm:px-6 lg:px-8">
            <div class="w-full max-w-4xl mx-auto">
                <!-- Header Section -->
                <div class="mb-8">
                    @if ($hasMultipleUnits)
                        <!-- Back Button - Desktop Only -->
                        <div class="hidden sm:block mb-4">
                            <a href="{{ route('schedule-link.index', ['company' => $company]) }}"
                                class="group inline-flex items-center px-4 py-3 bg-gray-800/60 hover:bg-gray-700/80 backdrop-blur-sm border border-gray-600/50 hover:border-gray-500/70 text-white text-sm font-medium rounded-2xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-2 focus:ring-offset-gray-800 shadow-lg hover:shadow-xl hover:scale-105 active:scale-95">
                                <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:-translate-x-1"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                                {{ __('schedule_link.back') }}
                            </a>
                        </div>
                    @endif

                    <!-- Title Section -->
                    <div class="text-center pt-16 sm:pt-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">
                            {{ __('schedule_link.title', ['unit' => $unit->name]) }}</h1>
                        <p class="text-gray-400 text-sm sm:text-base">{{ __('schedule_link.subtitle') }}</p>
                    </div>
                </div>

                <!-- Main Form Card -->
                <div
                    class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl sm:rounded-2xl shadow-2xl overflow-hidden">
                    <div class="p-4 sm:p-6 lg:p-8">
                        <form method="POST"
                            action="{{ route('schedule-link.store', ['company' => $company, 'unit' => $unit->id]) }}"
                            class="space-y-8" id="booking-form">
                            @csrf

                            <!-- Step 1: Personal Information -->
                            <div class="space-y-6">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div
                                        class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        1</div>
                                    <h2 class="text-xl font-semibold text-white">{{ __('schedule_link.personal_info') }}</h2>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                    <div class="space-y-2">
                                        <label for="name" class="block text-gray-300 text-sm font-medium">
                                            {{ __('schedule_link.name') }} <span class="text-red-400">*</span>
                                        </label>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                                            required placeholder="{{ __('schedule_link.name_placeholder') }}"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700/50 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                                    </div>
                                    <div class="space-y-2">
                                        <label for="phone" class="block text-gray-300 text-sm font-medium">
                                            {{ __('schedule_link.phone') }} <span class="text-red-400">*</span>
                                        </label>
                                        <input type="text" id="phone_display" value="{{ old('phone') }}"
                                            inputmode="numeric" required placeholder="{{ __('schedule_link.phone_placeholder') }}"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700/50 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <input type="hidden" id="phone" name="phone"
                                            value="{{ old('phone') ? preg_replace('/\D/', '', old('phone')) : '' }}">
                                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Service Selection -->
                            <div class="space-y-4" id="step-service">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div
                                        class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        2</div>
                                    <h2 class="text-xl font-semibold text-white">{{ __('schedules.service_type') }}</h2>
                                </div>

                                <div class="space-y-4">
                                    <label class="block text-gray-300 text-sm font-medium">
                                        {{ __('schedules.service_type') }} <span class="text-red-400">*</span>
                                    </label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" role="radiogroup"
                                        aria-label="{{ __('schedule_link.service_selection_aria') }}">
                                        @foreach ($serviceTypes as $type)
                                            <div class="relative">
                                                <input type="radio" id="service_{{ $type->id }}"
                                                    name="unit_service_type_id" value="{{ $type->id }}"
                                                    @checked(old('unit_service_type_id') == $type->id) class="sr-only" required>
                                                <label for="service_{{ $type->id }}"
                                                    class="block w-full h-24 p-4 rounded-xl border-2 border-gray-600 bg-gradient-to-br from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 cursor-pointer transition-all duration-300 shadow-lg hover:shadow-xl focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-800">
                                                    <div class="flex items-center space-x-3">
                                                        <div
                                                            class="w-5 h-5 rounded-full border-2 border-gray-400 flex items-center justify-center transition-all duration-200">
                                                            <div
                                                                class="w-2.5 h-2.5 rounded-full bg-blue-500 opacity-0 transition-all duration-200">
                                                            </div>
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="text-white font-medium">{{ $type->name }}
                                                            </div>
                                                            @if ($type->description)
                                                                <div class="text-gray-400 text-sm mt-1">
                                                                    {{ $type->description }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <x-input-error :messages="$errors->get('unit_service_type_id')" class="mt-1" />
                                </div>
                            </div>

                            <!-- Step 3: Date and Time Selection -->
                            <div class="space-y-6 hidden" id="step-date-time">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div
                                        class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        3</div>
                                    <h2 class="text-xl font-semibold text-white">{{ __('schedule_link.date_time') }}</h2>
                                </div>

                                <!-- Date Selection -->
                                <div class="space-y-4">
                                    <label class="block text-gray-300 text-sm font-medium">
                                        {{ __('schedule_link.choose_day') }} <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <!-- Week Navigation -->
                                        <div class="flex items-center justify-between mb-4">
                                            <button type="button" id="prev-week"
                                                class="p-2 rounded-lg bg-gray-700 hover:bg-gray-600 text-white transition-colors duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                </svg>
                                            </button>
                                            <div class="text-center">
                                                <div id="week-display" class="text-white font-medium"></div>
                                            </div>
                                            <button type="button" id="next-week"
                                                class="p-2 rounded-lg bg-gray-700 hover:bg-gray-600 text-white transition-colors duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Calendar Grid -->
                                        <div class="grid grid-cols-7 gap-2" id="calendar" role="group"
                                            aria-label="{{ __('schedule_link.date_selection_aria') }}">
                                            <!-- Days will be rendered here -->
                                        </div>

                                        <!-- Loading Spinner -->
                                        <div id="week-loading-spinner"
                                            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                            <div class="bg-gray-800 rounded-lg p-6 flex flex-col items-center">
                                                <div
                                                    class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mb-4">
                                                </div>
                                                <p class="text-white text-sm">{{ __('schedule_link.loading_week') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="schedule_date" id="schedule_date"
                                        value="{{ old('schedule_date') }}">
                                    <x-input-error :messages="$errors->get('schedule_date')" class="mt-1" />
                                </div>

                                <!-- Time Selection -->
                                <div class="space-y-4" id="step-time">
                                    <label class="block text-gray-300 text-sm font-medium">
                                        {{ __('schedule_link.choose_time') }} <span class="text-red-400">*</span>
                                    </label>
                                    <div id="times"
                                        class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-3"
                                        role="group" aria-label="{{ __('schedule_link.time_selection_aria') }}">
                                        <!-- Times will be rendered here -->
                                    </div>
                                    <input type="hidden" name="start_time" id="start_time"
                                        value="{{ old('start_time') }}">
                                    <x-input-error :messages="$errors->get('start_time')" class="mt-1" />
                                </div>
                            </div>



                            <!-- Error Messages -->
                            @if ($errors->has('general'))
                                <div class="bg-red-900/20 border border-red-700 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-red-400 text-sm">{{ $errors->first('general') }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Submit Button -->
                            <div class="flex justify-center pt-4 sm:pt-6">
                                <button type="submit" id="submit-button" disabled
                                    class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 disabled:from-gray-600 disabled:to-gray-700 disabled:cursor-not-allowed text-white font-semibold rounded-lg shadow-lg transform transition-all duration-200 hover:scale-105 disabled:transform-none focus:ring-4 focus:ring-blue-500/50 focus:outline-none">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ __('actions.save') }}
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    const weekStart = @json($weekStart);
    let weekDays = @json($weekDays);
    const unitId = @json($unit->id);
    const companyId = @json($company);
    let currentWeekStart = weekStart;

    // i18n strings
    const i18n = {
        daysShort: @json(__('schedule_link.days_short')),
        ariaSelectDate: @json(__('schedule_link.aria_select_date')),
        ariaSelectTime: @json(__('schedule_link.aria_select_time')),
        noTimesThisWeek: @json(__('schedule_link.no_times_this_week')),
        serviceNotAvailableAnyDay: @json(__('schedule_link.service_not_available_any_day')),
        serviceAvailableDaysMessage: @json(__('schedule_link.service_available_days_message')),
        noTimesThisDate: @json(__('schedule_link.no_times_this_date')),
        errorLoadingTimes: @json(__('schedule_link.error_loading_times')),
        submitRequiredAll: @json(__('schedule_link.submit_required_all')),
        submitSelectService: @json(__('schedule_link.submit_select_service')),
        submitSelectDate: @json(__('schedule_link.submit_select_date')),
        submitSelectTime: @json(__('schedule_link.submit_select_time')),
    };

    // Service types data with week days availability
    const serviceTypes = @json($serviceTypes);

    let selectedServiceType = null;

    function renderCalendar(weekStartStr) {
        const calendar = document.getElementById('calendar');
        calendar.innerHTML = '';

        // Render all 7 days of the week
        weekDays.forEach((dayData) => {
            const card = document.createElement('button');
            card.type = 'button';
            card.className =
                'flex flex-col items-center justify-center h-20 sm:h-24 rounded-xl border-2 transition-all duration-300 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800';
            card.setAttribute('aria-label', i18n.ariaSelectDate.replace(':day', dayData.day).replace(':month', dayData.month));
            card.setAttribute('data-date', dayData.date);

            // Check if the day is available for the selected service type
            let isServiceAvailable = true;
            if (selectedServiceType) {
                const dayName = getDayNameForService(dayData.day_of_week);
                isServiceAvailable = selectedServiceType[dayName];
            }

            if (dayData.is_today) {
                card.classList.add('bg-gradient-to-br', 'from-yellow-400', 'to-yellow-500', 'border-yellow-400',
                    'text-black', 'shadow-yellow-500/25');
            } else if (dayData.available && isServiceAvailable) {
                card.classList.add('bg-green-50', 'dark:bg-green-900/20', 'border-green-200',
                    'dark:border-green-800', 'text-green-800', 'dark:text-green-200', 'hover:bg-green-100',
                    'dark:hover:bg-green-900/30');
                card.addEventListener('click', () => onSelectDate(dayData.date, card));
                card.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        onSelectDate(dayData.date, card);
                    }
                });
            } else {
                card.classList.add('bg-gradient-to-br', 'from-gray-800', 'to-gray-900', 'border-transparent',
                    'text-gray-400', 'cursor-not-allowed');
                card.disabled = true;
            }

            if (dayData.available) {
                card.innerHTML = `
                    <div class="text-xs font-semibold opacity-80">${getDayName(dayData.day_of_week)}</div>
                    <div class="text-lg sm:text-xl font-bold leading-tight">${dayData.day}</div>
                    <div class="text-xs opacity-80">${dayData.month}</div>
                `;
            } else {
                card.innerHTML = `
                    <div class="text-xs font-semibold opacity-60 line-through">${getDayName(dayData.day_of_week)}</div>
                    <div class="text-lg sm:text-xl font-bold leading-tight line-through">${dayData.day}</div>
                    <div class="text-xs opacity-60 line-through">${dayData.month}</div>
                `;
            }

            calendar.appendChild(card);
        });

        // Update week display
        updateWeekDisplay(weekStartStr);

        // Auto-select first available day (without scroll)
        const firstAvailableDay = weekDays.find(day => {
            if (!day.available) return false;
            if (!selectedServiceType) return true;
            const dayName = getDayNameForService(day.day_of_week);
            return selectedServiceType[dayName];
        });
        if (firstAvailableDay) {
            const firstDayCard = calendar.querySelector(`[data-date="${firstAvailableDay.date}"]`);
            if (firstDayCard) {
                // Select the date without scrolling
                onSelectDate(firstAvailableDay.date, firstDayCard, false);
            }
        } else {
            // Clear times if no available days
            const timesEl = document.getElementById('times');
            let message = i18n.noTimesThisWeek;

            if (selectedServiceType) {
                const availableDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']
                    .filter(day => selectedServiceType[day]).length;

                if (availableDays === 0) {
                    message = i18n.serviceNotAvailableAnyDay;
                } else {
                    message = i18n.serviceAvailableDaysMessage.replace(':count', availableDays);
                }
            }

            timesEl.innerHTML = `
                 <div class="col-span-full text-center py-8">
                     <div class="text-gray-400 text-sm">
                         <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                         </svg>
                         ${message}
                     </div>
                 </div>
             `;

            // Clear selected date and time
            document.getElementById('schedule_date').value = '';
            document.getElementById('start_time').value = '';
            updateSubmitEnabled();
        }
    }

    function getDayName(dayOfWeek) {
        return i18n.daysShort[dayOfWeek];
    }

    function getDayNameForService(dayOfWeek) {
        const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        return days[dayOfWeek];
    }

    function onSelectDate(dateStr, btn, shouldScroll = true) {
        // Remove selection from all day cards
        document.querySelectorAll('#calendar button').forEach(b => {
            b.classList.remove('ring-2', 'ring-green-500', 'scale-105');
            if (b.classList.contains('from-yellow-400')) {
                b.classList.add('bg-gradient-to-br', 'from-yellow-400', 'to-yellow-500', 'border-yellow-400',
                    'text-black', 'shadow-yellow-500/25');
            } else if (b.classList.contains('bg-green-50')) {
                b.classList.add('bg-green-50', 'dark:bg-green-900/20', 'border-green-200',
                    'dark:border-green-800', 'text-green-800', 'dark:text-green-200');
            } else {
                b.classList.add('bg-gradient-to-br', 'from-gray-800', 'to-gray-900', 'border-transparent',
                    'text-gray-400');
            }
        });

        // Add selection to clicked card
        btn.classList.add('ring-2', 'ring-green-500', 'scale-105');
        document.getElementById('schedule_date').value = dateStr;

        // Scroll to time selection only if shouldScroll is true (user interaction)
        if (shouldScroll) {
            setTimeout(() => {
                document.getElementById('step-time').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 500);
        }

        // Show loading state
        const timesEl = document.getElementById('times');
        timesEl.innerHTML =
            '<div class="col-span-full flex justify-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div></div>';

        fetchTimes(dateStr, shouldScroll);
    }

    function fetchTimes(dateStr, shouldScroll = true) {
        const timesEl = document.getElementById('times');

        fetch(`${window.location.origin}/${companyId}/schedule-link/${unitId}/available-times?date=${dateStr}`)
            .then(r => r.json())
            .then(data => {
                timesEl.innerHTML = '';

                if (!data.times || data.times.length === 0) {
                    timesEl.innerHTML = `
                            <div class="col-span-full text-center py-8">
                                <div class="text-gray-400 text-sm">
                                    <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    ${i18n.noTimesThisDate}
                                </div>
                            </div>
                        `;
                    return;
                }

                (data.times || []).forEach(time => {
                    const b = document.createElement('button');
                    b.type = 'button';
                    b.className =
                        'px-3 sm:px-4 py-3 sm:py-4 rounded-xl bg-gradient-to-br from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 text-white text-sm font-semibold transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 border border-gray-600 hover:border-gray-500';
                    b.textContent = time;
                    b.setAttribute('aria-label', i18n.ariaSelectTime.replace(':time', time));

                    b.addEventListener('click', () => {
                        document.querySelectorAll('#times button').forEach(x => {
                            x.classList.remove('ring-2', 'ring-blue-500', 'scale-105',
                                'from-blue-600', 'to-blue-700', 'hover:from-blue-700',
                                'hover:to-blue-800');
                            x.classList.add('from-gray-700', 'to-gray-800',
                                'hover:from-gray-600', 'hover:to-gray-700');
                        });
                        b.classList.add('ring-2', 'ring-blue-500', 'scale-105', 'from-blue-600',
                            'to-blue-700', 'hover:from-blue-700', 'hover:to-blue-800');
                        document.getElementById('start_time').value = time;
                        updateSubmitEnabled();

                        // Scroll to submit button only if shouldScroll is true (user interaction)
                        if (shouldScroll) {
                            setTimeout(() => {
                                document.getElementById('submit-button').scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            }, 300);
                        }
                    });

                    b.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            b.click();
                        }
                    });

                    timesEl.appendChild(b);
                });
                updateSubmitEnabled();
            })
            .catch(() => {
                timesEl.innerHTML = `
                        <div class="col-span-full text-center py-8">
                            <div class="text-red-400 text-sm">
                                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                ${i18n.errorLoadingTimes}
                            </div>
                        </div>
                    `;
                updateSubmitEnabled();
            });
    }

    function updateSubmitEnabled() {
        const name = document.querySelector('input[name="name"]').value.trim();
        const phone = document.querySelector('input[name="phone"]').value.trim();
        const service = document.querySelector('input[name="unit_service_type_id"]:checked')?.value || '';
        const date = document.getElementById('schedule_date').value;
        const time = document.getElementById('start_time').value;

        // If no service is selected, only require name and phone
        const can = service ? (name && phone && service && date && time) : (name && phone);

        const submitBtn = document.getElementById('submit-button');
        submitBtn.disabled = !can;

        // Update button text based on state
        const btnText = submitBtn.querySelector('span');
        if (can) {
            btnText.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('actions.save') }}
                `;
        } else {
            let message = i18n.submitRequiredAll;
            if (!service) {
                message = i18n.submitSelectService;
            } else if (!date) {
                message = i18n.submitSelectDate;
            } else if (!time) {
                message = i18n.submitSelectTime;
            }

            btnText.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ${message}
                `;
        }
    }



    // Week navigation functions
    function loadWeek(weekStartStr) {
        const prevBtn = document.getElementById('prev-week');
        const nextBtn = document.getElementById('next-week');
        const spinner = document.getElementById('week-loading-spinner');

        // Show spinner and disable navigation buttons during loading
        spinner.classList.remove('hidden');
        prevBtn.disabled = true;
        nextBtn.disabled = true;

        fetch(`${window.location.origin}/${companyId}/schedule-link/${unitId}/week-days?week_start=${weekStartStr}`)
            .then(r => r.json())
            .then(data => {
                if (data.days && data.days.length > 0) {
                    weekDays = data.days;
                    currentWeekStart = weekStartStr;
                    renderCalendar(weekStartStr);
                }
            })
            .catch(() => {
                // Handle error silently
            })
            .finally(() => {
                // Hide spinner and re-enable navigation buttons
                spinner.classList.add('hidden');
                prevBtn.disabled = false;
                nextBtn.disabled = false;
            });
    }

    function updateWeekDisplay(weekStartStr) {
        const weekDisplay = document.getElementById('week-display');
        const startDate = new Date(weekStartStr);
        const endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + 6);

        const startFormatted = startDate.toLocaleDateString('pt-BR', {
            day: 'numeric',
            month: 'short'
        });
        const endFormatted = endDate.toLocaleDateString('pt-BR', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });

        weekDisplay.textContent = `${startFormatted} - ${endFormatted}`;
    }

    function navigateWeek(direction) {
        const currentDate = new Date(currentWeekStart);
        const newDate = new Date(currentDate);

        if (direction === 'next') {
            newDate.setDate(currentDate.getDate() + 7);
        } else {
            newDate.setDate(currentDate.getDate() - 7);
        }

        const newWeekStart = newDate.toISOString().split('T')[0];
        loadWeek(newWeekStart);
    }

    // Phone mask function
    function formatPhoneNumber(input) {
        let value = input.value.replace(/\D/g, '').substring(0, 11);
        let formattedValue = '';

        if (value.length > 0) {
            let ddd = value.substring(0, 2);
            let firstPart = '';
            let secondPart = '';

            if (value.length >= 7) {
                if (value.length === 11) {
                    firstPart = value.substring(2, 7);
                    secondPart = value.substring(7, 11);
                } else {
                    firstPart = value.substring(2, 6);
                    secondPart = value.substring(6, 10);
                }
            } else {
                firstPart = value.substring(2);
            }

            formattedValue = `(${ddd}) ${firstPart}${secondPart ? '-' + secondPart : ''}`;
        }

        input.value = formattedValue;

        // Update hidden field with only numbers
        const hiddenPhoneInput = document.getElementById('phone');
        if (hiddenPhoneInput) {
            hiddenPhoneInput.value = value;
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('input', updateSubmitEnabled);
        renderCalendar(weekStart);
        updateSubmitEnabled();

        // Enable smooth scrolling for user interactions
        document.addEventListener('click', function() {
            document.documentElement.classList.add('user-interaction');
        }, {
            once: true
        });

        // Add week navigation functionality
        document.getElementById('prev-week').addEventListener('click', () => navigateWeek('prev'));
        document.getElementById('next-week').addEventListener('click', () => navigateWeek('next'));

        // Add radio button functionality for service types
        document.querySelectorAll('input[name="unit_service_type_id"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected state from all labels
                document.querySelectorAll('label[for^="service_"]').forEach(label => {
                    label.classList.remove('border-blue-500', 'from-blue-600',
                        'to-blue-700');
                    label.classList.add('border-gray-600', 'from-gray-700',
                        'to-gray-800');
                    const radioCircle = label.querySelector('.w-5.h-5');
                    const radioDot = label.querySelector('.w-2\\.5.h-2\\.5');
                    radioCircle.classList.remove('border-blue-500');
                    radioCircle.classList.add('border-gray-400');
                    radioDot.classList.remove('opacity-100');
                    radioDot.classList.add('opacity-0');
                });

                // Add selected state to current label
                if (this.checked) {
                    const label = document.querySelector(`label[for="${this.id}"]`);
                    label.classList.remove('border-gray-600', 'from-gray-700', 'to-gray-800');
                    label.classList.add('border-blue-500', 'from-blue-600', 'to-blue-700');
                    const radioCircle = label.querySelector('.w-5.h-5');
                    const radioDot = label.querySelector('.w-2\\.5.h-2\\.5');
                    radioCircle.classList.remove('border-gray-400');
                    radioCircle.classList.add('border-blue-500');
                    radioDot.classList.remove('opacity-0');
                    radioDot.classList.add('opacity-100');

                    // Set selected service type
                    selectedServiceType = serviceTypes.find(type => type.id == this.value);

                    // Show date and time section
                    const dateTimeSection = document.getElementById('step-date-time');
                    dateTimeSection.classList.remove('hidden');

                    // Clear previous selections
                    document.getElementById('schedule_date').value = '';
                    document.getElementById('start_time').value = '';
                    document.getElementById('times').innerHTML = '';

                    // Re-render calendar with service type filter
                    renderCalendar(currentWeekStart);

                    // Scroll to next step (Date and Time) only on user interaction
                    if (this.hasAttribute('data-user-interaction')) {
                        setTimeout(() => {
                            dateTimeSection.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }, 300);
                    }
                } else {
                    // Hide date and time section if no service is selected
                    document.getElementById('step-date-time').classList.add('hidden');
                    selectedServiceType = null;
                }

                // Update submit button state
                updateSubmitEnabled();
            });

            // Add click event to mark user interaction
            radio.addEventListener('click', function() {
                this.setAttribute('data-user-interaction', 'true');
            });
        });

        // Trigger change event for pre-selected radio button (without scroll)
        const preSelectedRadio = document.querySelector('input[name="unit_service_type_id"]:checked');
        if (preSelectedRadio) {
            preSelectedRadio.dispatchEvent(new Event('change'));
        } else {
            // If no service is pre-selected, hide the date/time section
            document.getElementById('step-date-time').classList.add('hidden');
        }

        // Add phone mask functionality
        const phoneDisplayInput = document.getElementById('phone_display');
        const phoneHiddenInput = document.getElementById('phone');

        if (phoneDisplayInput && phoneHiddenInput) {
            // Apply mask on input
            phoneDisplayInput.addEventListener('input', function() {
                formatPhoneNumber(this);
            });

            // Apply mask on page load if there's a value
            if (phoneDisplayInput.value) {
                formatPhoneNumber(phoneDisplayInput);
            }
        }
    });
</script>

</html>
