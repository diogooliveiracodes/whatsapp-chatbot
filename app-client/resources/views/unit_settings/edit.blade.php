<x-app-layout>
    <x-global.header>
        {{ __('unitSettings.edit') }} - {{ $unitSettings->unit->name }}
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

                    <form action="{{ route('unitSettings.update', $unitSettings->unit->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Basic Information -->
                            <x-forms.section-title :title="__('unitSettings.basic_info_section')" />

                            <div>
                                <x-unit-settings.text-input name="name" :label="__('unitSettings.name')" :value="$unitSettings->name"
                                    :required="true" />
                            </div>

                            <div>
                                <x-unit-settings.text-input name="phone" :label="__('unitSettings.phone')" :value="$unitSettings->phone" />
                            </div>

                            <!-- Address Information -->
                            <x-forms.section-title :title="__('unitSettings.address_section')" />

                            <div>
                                <x-unit-settings.text-input name="street" :label="__('unitSettings.street')" :value="$unitSettings->street" />
                            </div>

                            <div>
                                <x-unit-settings.text-input name="number" :label="__('unitSettings.number')" :value="$unitSettings->number" />
                            </div>

                            <div>
                                <x-unit-settings.text-input name="complement" :label="__('unitSettings.complement')" :value="$unitSettings->complement" />
                            </div>

                            <div>
                                <x-unit-settings.text-input name="neighborhood" :label="__('unitSettings.neighborhood')" :value="$unitSettings->neighborhood" />
                            </div>

                            <div>
                                <x-unit-settings.text-input name="city" :label="__('unitSettings.city')" :value="$unitSettings->city" />
                            </div>

                            <div>
                                <x-unit-settings.text-input name="state" :label="__('unitSettings.state')" :value="$unitSettings->state" />
                            </div>

                            <div>
                                <x-unit-settings.text-input name="zipcode" :label="__('unitSettings.zipcode')" :value="$unitSettings->zipcode" />
                            </div>

                            <!-- WhatsApp Configuration -->
                            <x-forms.section-title :title="__('unitSettings.whatsapp_section')" />

                            <div>
                                <x-unit-settings.text-input name="whatsapp_webhook_url" :label="__('unitSettings.whatsapp_webhook_url')"
                                    :value="$unitSettings->whatsapp_webhook_url" />
                            </div>

                            <div>
                                <x-unit-settings.text-input name="whatsapp_number" :label="__('unitSettings.whatsapp_number')"
                                    :value="$unitSettings->whatsapp_number" />
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
                                        <x-unit-settings.day-time-input :day="$day" :label="$label"
                                            :isChecked="old($day, $unitSettings->$day)" :startTime="old($day . '_start', $unitSettings->{$day . '_start'})" :endTime="old($day . '_end', $unitSettings->{$day . '_end'})" />
                                    @endforeach
                                </div>
                            </div>

                            <!-- Additional Settings -->
                            <x-forms.section-title :title="__('unitSettings.additional_settings_section')" />

                            <div>
                                <label for="default_language"
                                    class="label-style">{{ __('unitSettings.default_language') }}</label>
                                <select id="default_language" name="default_language" class="input-style">
                                    <option value="pt_BR"
                                        {{ old('default_language', $unitSettings->default_language) == 'pt_BR' ? 'selected' : '' }}>
                                        Português</option>
                                    <option value="en"
                                        {{ old('default_language', $unitSettings->default_language) == 'en' ? 'selected' : '' }}>
                                        English</option>
                                </select>
                            </div>

                            <div>
                                <label for="timezone" class="label-style">{{ __('unitSettings.timezone') }}</label>
                                <select id="timezone" name="timezone" class="input-style">
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

                        <!-- Action Buttons -->
                        <div class="mt-6 flex justify-between">
                            <!-- Back Button -->
                            <x-cancel-link href="{{ route('units.show', $unitSettings->unit->id) }}">
                                {{ __('unitSettings.back') }}
                            </x-cancel-link>

                            <!-- Save Button -->
                            <x-primary-button type="submit">
                                {{ __('unitSettings.save_changes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
