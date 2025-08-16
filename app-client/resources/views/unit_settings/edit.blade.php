<x-app-layout>
    <x-global.header>
        <div class="flex items-center space-x-3">
            <span>{{ __('unitSettings.edit') }}</span>
        </div>
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if (session('error'))
                <div class="mb-6 p-4 text-sm text-red-700 bg-red-100 border border-red-200 rounded-lg dark:bg-red-900/20 dark:text-red-400 dark:border-red-800"
                    role="alert">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 text-sm text-green-700 bg-green-100 border border-green-200 rounded-lg dark:bg-green-900/20 dark:text-green-400 dark:border-green-800"
                    role="alert">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Main Form -->
            <div class="bg-gray-800 shadow-xl sm:rounded-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4 border-b border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-white">{{ $unitSettings->name }}</h2>
                            <p class="text-gray-400 text-sm">{{ $unitSettings->unit->name }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('unitSettings.update', $unitSettings->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6 space-y-8">
                        <!-- Basic Information Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-white">{{ __('unitSettings.basic_info_section') }}
                                </h3>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <x-unit-settings.text-input name="name" :label="__('unitSettings.name')" :value="$unitSettings->name"
                                        :required="true" />
                                </div>

                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <x-unit-settings.text-input name="phone" :label="__('unitSettings.phone')" :value="$unitSettings->phone" />
                                </div>
                            </div>
                        </div>

                        <!-- Address Information Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-white">{{ __('unitSettings.address_section') }}</h3>
                            </div>

                            <div class="bg-gray-700/50 rounded-lg p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div>
                                        <x-unit-settings.text-input name="street" :label="__('unitSettings.street')"
                                            :value="$unitSettings->street" />
                                    </div>
                                    <div>
                                        <x-unit-settings.text-input name="number" :label="__('unitSettings.number')"
                                            :value="$unitSettings->number" />
                                    </div>
                                    <div>
                                        <x-unit-settings.text-input name="complement" :label="__('unitSettings.complement')"
                                            :value="$unitSettings->complement" />
                                    </div>
                                    <div>
                                        <x-unit-settings.text-input name="neighborhood" :label="__('unitSettings.neighborhood')"
                                            :value="$unitSettings->neighborhood" />
                                    </div>
                                    <div>
                                        <x-unit-settings.text-input name="city" :label="__('unitSettings.city')"
                                            :value="$unitSettings->city" />
                                    </div>
                                    <div>
                                        <x-unit-settings.text-input name="state" :label="__('unitSettings.state')"
                                            :value="$unitSettings->state" />
                                    </div>
                                    <div class="sm:col-span-2 lg:col-span-1">
                                        <x-unit-settings.text-input name="zipcode" :label="__('unitSettings.zipcode')"
                                            :value="$unitSettings->zipcode" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- WhatsApp Configuration Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                                </svg>
                                <h3 class="text-lg font-medium text-white">{{ __('unitSettings.whatsapp_section') }}
                                </h3>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <x-unit-settings.text-input name="whatsapp_webhook_url" :label="__('unitSettings.whatsapp_webhook_url')"
                                        :value="$unitSettings->whatsapp_webhook_url" />
                                </div>

                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <x-unit-settings.text-input name="whatsapp_number" :label="__('unitSettings.whatsapp_number')"
                                        :value="$unitSettings->whatsapp_number" />
                                </div>
                            </div>
                        </div>

                        <!-- Working Hours Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-white">
                                    {{ __('unitSettings.working_hours_section') }}</h3>
                            </div>

                            <div class="bg-gray-700/50 rounded-lg p-6 space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <x-unit-settings.text-input name="appointment_duration_minutes"
                                            :label="__('unitSettings.appointment_duration_minutes')" :value="old(
                                                'appointment_duration_minutes',
                                                $unitSettings->appointment_duration_minutes ?? '',
                                            )" :required="true" />
                                    </div>
                                </div>

                                <div class="border-t border-gray-600 pt-6">
                                    <div class="flex items-center space-x-2 mb-4">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <label
                                            class="text-sm font-medium text-gray-300">{{ __('unitSettings.working_days') }}</label>
                                    </div>

                                    <div class="space-y-4">
                                        @php
                                            $days = [
                                                'sunday' => __('unitSettings.sunday'),
                                                'monday' => __('unitSettings.monday'),
                                                'tuesday' => __('unitSettings.tuesday'),
                                                'wednesday' => __('unitSettings.wednesday'),
                                                'thursday' => __('unitSettings.thursday'),
                                                'friday' => __('unitSettings.friday'),
                                                'saturday' => __('unitSettings.saturday'),
                                            ];
                                        @endphp

                                        @foreach ($days as $day => $label)
                                            @php
                                                $userTimezone = auth()->user()->unit->unitSettings->timezone ?? 'UTC';
                                                $referenceDate = now()->format('Y-m-d');

                                                $rawStart = old($day . '_start', $unitSettings->{$day . '_start'});
                                                $rawEnd = old($day . '_end', $unitSettings->{$day . '_end'});

                                                $startDisplay = $rawStart
                                                    ? \Carbon\Carbon::parse($referenceDate . ' ' . $rawStart, 'UTC')
                                                        ->setTimezone($userTimezone)
                                                        ->format('H:i')
                                                    : '';
                                                $endDisplay = $rawEnd
                                                    ? \Carbon\Carbon::parse($referenceDate . ' ' . $rawEnd, 'UTC')
                                                        ->setTimezone($userTimezone)
                                                        ->format('H:i')
                                                    : '';
                                            @endphp

                                            <x-unit-settings.day-time-input :day="$day" :label="$label"
                                                :isChecked="old($day, $unitSettings->$day)" :startTime="$startDisplay" :endTime="$endDisplay" />
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Settings Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-white">
                                    {{ __('unitSettings.additional_settings_section') }}</h3>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129">
                                            </path>
                                        </svg>
                                        <label for="default_language"
                                            class="text-sm font-medium text-gray-300">{{ __('unitSettings.default_language') }}</label>
                                    </div>
                                    <select id="default_language" name="default_language" class="input-style"
                                        disabled>
                                        <option value="pt_BR"
                                            {{ old('default_language', $unitSettings->default_language) == 'pt_BR' ? 'selected' : '' }}>
                                            Português</option>
                                        <option value="en"
                                            {{ old('default_language', $unitSettings->default_language) == 'en' ? 'selected' : '' }}>
                                            English</option>
                                    </select>
                                </div>

                                <div class="bg-gray-700/50 rounded-lg p-4">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        <label for="timezone"
                                            class="text-sm font-medium text-gray-300">{{ __('unitSettings.timezone') }}</label>
                                    </div>
                                    <select id="timezone" name="timezone" class="input-style" disabled>
                                        <option value="America/Sao_Paulo"
                                            {{ old('timezone', $unitSettings->timezone) == 'America/Sao_Paulo' ? 'selected' : '' }}>
                                            Brasília (GMT-3)</option>
                                        <option value="America/New_York"
                                            {{ old('timezone', $unitSettings->timezone) == 'America/New_York' ? 'selected' : '' }}>
                                            New York (GMT-4)</option>
                                        <option value="Europe/London"
                                            {{ old('timezone', $unitSettings->timezone) == 'Europe/London' ? 'selected' : '' }}>
                                            London (GMT+1)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-gray-700/50 px-6 py-4 border-t border-gray-700">
                        <div
                            class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4">
                            <!-- Back Button -->
                            <x-cancel-link href="{{ route('units.show', $unitSettings->unit->id) }}"
                                class="w-full sm:w-auto">
                                {{ __('unitSettings.back') }}
                            </x-cancel-link>

                            <!-- Save Button -->
                            <x-primary-button type="submit" class="w-full sm:w-auto">
                                {{ __('actions.save') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


</x-app-layout>
