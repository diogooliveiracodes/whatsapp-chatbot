<x-app-layout>
    <x-header>
        {{ __('unitSettings.edit') }} - {{ $unitSettings->unit->name }}
    </x-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-100 space-y-6">
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

                    <form action="{{ route('unitSettings.update', $unitSettings->unit->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Basic Information -->
                            <div>
                                <label for="name" class="label-style">{{ __('unitSettings.name') }}</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $unitSettings->name) }}"
                                       class="input-style"
                                       required>
                            </div>

                            <div>
                                <label for="phone" class="label-style">{{ __('unitSettings.phone') }}</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $unitSettings->phone) }}"
                                       class="input-style">
                            </div>

                            <!-- Address Information -->
                            <div>
                                <label for="street" class="label-style">{{ __('unitSettings.street') }}</label>
                                <input type="text" id="street" name="street" value="{{ old('street', $unitSettings->street) }}"
                                       class="input-style">
                            </div>

                            <div>
                                <label for="number" class="label-style">{{ __('unitSettings.number') }}</label>
                                <input type="text" id="number" name="number" value="{{ old('number', $unitSettings->number) }}"
                                       class="input-style">
                            </div>

                            <div>
                                <label for="complement" class="label-style">{{ __('unitSettings.complement') }}</label>
                                <input type="text" id="complement" name="complement" value="{{ old('complement', $unitSettings->complement) }}"
                                       class="input-style">
                            </div>

                            <div>
                                <label for="neighborhood" class="label-style">{{ __('unitSettings.neighborhood') }}</label>
                                <input type="text" id="neighborhood" name="neighborhood" value="{{ old('neighborhood', $unitSettings->neighborhood) }}"
                                       class="input-style">
                            </div>

                            <div>
                                <label for="city" class="label-style">{{ __('unitSettings.city') }}</label>
                                <input type="text" id="city" name="city" value="{{ old('city', $unitSettings->city) }}"
                                       class="input-style">
                            </div>

                            <div>
                                <label for="state" class="label-style">{{ __('unitSettings.state') }}</label>
                                <input type="text" id="state" name="state" value="{{ old('state', $unitSettings->state) }}"
                                       class="input-style">
                            </div>

                            <div>
                                <label for="zipcode" class="label-style">{{ __('unitSettings.zipcode') }}</label>
                                <input type="text" id="zipcode" name="zipcode" value="{{ old('zipcode', $unitSettings->zipcode) }}"
                                       class="input-style">
                            </div>

                            <!-- WhatsApp Configuration -->
                            <div>
                                <label for="whatsapp_webhook_url" class="label-style">{{ __('unitSettings.whatsapp_webhook_url') }}</label>
                                <input type="text" id="whatsapp_webhook_url" name="whatsapp_webhook_url"
                                       value="{{ old('whatsapp_webhook_url', $unitSettings->whatsapp_webhook_url) }}"
                                       class="input-style">
                            </div>

                            <div>
                                <label for="whatsapp_number" class="label-style">{{ __('unitSettings.whatsapp_number') }}</label>
                                <input type="text" id="whatsapp_number" name="whatsapp_number"
                                       value="{{ old('whatsapp_number', $unitSettings->whatsapp_number) }}"
                                       class="input-style">
                            </div>

                            <!-- Working Hours -->
                            <div>
                                <label for="working_hour_start" class="label-style">{{ __('unitSettings.working_hour_start') }}</label>
                                <input type="time" id="working_hour_start" name="working_hour_start"
                                       value="{{ old('working_hour_start', $unitSettings->working_hour_start) }}"
                                       class="input-style">
                            </div>

                            <div>
                                <label for="working_hour_end" class="label-style">{{ __('unitSettings.working_hour_end') }}</label>
                                <input type="time" id="working_hour_end" name="working_hour_end"
                                       value="{{ old('working_hour_end', $unitSettings->working_hour_end) }}"
                                       class="input-style">
                            </div>

                            <div>
                                <label for="working_day_start" class="label-style">{{ __('unitSettings.working_day_start') }}</label>
                                <select id="working_day_start" name="working_day_start" class="input-style">
                                    @foreach(range(1, 7) as $day)
                                        <option value="{{ $day }}" {{ old('working_day_start', $unitSettings->working_day_start) == $day ? 'selected' : '' }}>
                                            {{ __('unitSettings.days.' . $day) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="working_day_end" class="label-style">{{ __('unitSettings.working_day_end') }}</label>
                                <select id="working_day_end" name="working_day_end" class="input-style">
                                    @foreach(range(1, 7) as $day)
                                        <option value="{{ $day }}" {{ old('working_day_end', $unitSettings->working_day_end) == $day ? 'selected' : '' }}>
                                            {{ __('unitSettings.days.' . $day) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Additional Settings -->
                            <div>
                                <label for="use_ai_chatbot" class="label-style">{{ __('unitSettings.use_ai_chatbot') }}</label>
                                <div class="mt-1">
                                    <label class="inline-flex items-center mr-4">
                                        <input type="radio" id="use_ai_chatbot_yes" name="use_ai_chatbot" value="1"
                                               {{ old('use_ai_chatbot', $unitSettings->use_ai_chatbot) == 1 ? 'checked' : '' }}
                                               class="form-radio text-indigo-500">
                                        <span class="ml-2 dark:text-gray-300">{{ __('unitSettings.yes') }}</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" id="use_ai_chatbot_no" name="use_ai_chatbot" value="0"
                                               {{ old('use_ai_chatbot', $unitSettings->use_ai_chatbot) == 0 ? 'checked' : '' }}
                                               class="form-radio text-indigo-500">
                                        <span class="ml-2 dark:text-gray-300">{{ __('unitSettings.no') }}</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="default_language" class="label-style">{{ __('unitSettings.default_language') }}</label>
                                <select id="default_language" name="default_language" class="input-style">
                                    <option value="pt_BR" {{ old('default_language', $unitSettings->default_language) == 'pt_BR' ? 'selected' : '' }}>Português</option>
                                    <option value="en" {{ old('default_language', $unitSettings->default_language) == 'en' ? 'selected' : '' }}>English</option>
                                </select>
                            </div>

                            <div>
                                <label for="timezone" class="label-style">{{ __('unitSettings.timezone') }}</label>
                                <select id="timezone" name="timezone" class="input-style">
                                    <option value="America/Sao_Paulo" {{ old('timezone', $unitSettings->timezone) == 'America/Sao_Paulo' ? 'selected' : '' }}>Brasília (GMT-3)</option>
                                    <option value="America/New_York" {{ old('timezone', $unitSettings->timezone) == 'America/New_York' ? 'selected' : '' }}>New York (GMT-4)</option>
                                    <option value="Europe/London" {{ old('timezone', $unitSettings->timezone) == 'Europe/London' ? 'selected' : '' }}>London (GMT+1)</option>
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
