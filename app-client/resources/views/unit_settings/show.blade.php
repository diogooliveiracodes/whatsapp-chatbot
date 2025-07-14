<x-app-layout>
    <x-global.header>
        {{ __('unitSettings.title') }} - {{ $unitSettings->unit->name }}
    </x-global.header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-100 space-y-6">
                    @if (session('error'))
                        <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                            role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                            role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Basic Information -->
                        <x-forms.section-title :title="__('unitSettings.basic_info_section')" />

                        <div>
                            <label class="label-style">{{ __('unitSettings.name') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->name }}</p>
                        </div>

                        <div>
                            <label class="label-style">{{ __('unitSettings.phone') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->phone ?? '-' }}</p>
                        </div>

                        <!-- Address Information -->
                        <x-forms.section-title :title="__('unitSettings.address_section')" />

                        <div>
                            <label class="label-style">{{ __('unitSettings.street') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->street ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="label-style">{{ __('unitSettings.number') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->number ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="label-style">{{ __('unitSettings.complement') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->complement ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="label-style">{{ __('unitSettings.neighborhood') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->neighborhood ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="label-style">{{ __('unitSettings.city') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->city ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="label-style">{{ __('unitSettings.state') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->state ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="label-style">{{ __('unitSettings.zipcode') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->zipcode ?? '-' }}</p>
                        </div>

                        <!-- WhatsApp Configuration -->
                        <x-forms.section-title :title="__('unitSettings.whatsapp_section')" />

                        <div>
                            <label class="label-style">{{ __('unitSettings.whatsapp_webhook_url') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->whatsapp_webhook_url ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="label-style">{{ __('unitSettings.whatsapp_number') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->whatsapp_number ?? '-' }}</p>
                        </div>

                        <!-- Working Hours -->
                        <x-forms.section-title :title="__('unitSettings.working_hours_section')" />

                        <div class="col-span-2">
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
                                    @if($unitSettings->$day)
                                        <div class="flex items-center space-x-4">
                                            <p class="text-md dark:text-gray-300">{{ $label }}</p>
                                            <p class="text-md dark:text-gray-300">
                                                {{ $unitSettings->{$day . '_start'} }} - {{ $unitSettings->{$day . '_end'} }}
                                            </p>
                                        </div>
                                    @endif
                                @endforeach
                                @if(!$unitSettings->sunday && !$unitSettings->monday && !$unitSettings->tuesday && !$unitSettings->wednesday && !$unitSettings->thursday && !$unitSettings->friday && !$unitSettings->saturday)
                                    <p class="text-md dark:text-gray-300">-</p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="label-style">{{ __('unitSettings.appointment_duration_minutes') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->appointment_duration_minutes ?? '-' }}</p>
                        </div>

                        <!-- Additional Settings -->
                        <x-forms.section-title :title="__('unitSettings.additional_settings_section')" />

                        <div>
                            <label class="label-style">{{ __('unitSettings.default_language') }}</label>
                            <p class="text-md dark:text-gray-300">
                                {{ $unitSettings->default_language == 'pt_BR' ? 'Português' : 'English' }}
                            </p>
                        </div>

                        <div>
                            <label class="label-style">{{ __('unitSettings.timezone') }}</label>
                            <p class="text-md dark:text-gray-300">
                                @switch($unitSettings->timezone)
                                    @case('America/Sao_Paulo')
                                        Brasília (GMT-3)
                                        @break
                                    @case('America/New_York')
                                        New York (GMT-4)
                                        @break
                                    @case('Europe/London')
                                        London (GMT+1)
                                        @break
                                    @default
                                        {{ $unitSettings->timezone ?? '-' }}
                                @endswitch
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex justify-between">
                        <!-- Back Button -->
                        <x-cancel-link href="{{ route('units.show', $unitSettings->unit->id) }}">
                            {{ __('unitSettings.back') }}
                        </x-cancel-link>

                        <!-- Edit Button -->
                        <x-confirm-link href="{{ route('unitSettings.edit', $unitSettings->id) }}">
                            {{ __('unitSettings.edit') }}
                        </x-confirm-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
