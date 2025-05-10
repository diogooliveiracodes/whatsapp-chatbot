<x-app-layout>

    <x-header>
        {{ __('pages.companySettings') }}
    </x-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-lg sm:rounded-lg">

                @if (session('success'))
                    <div class="bg-green-600 text-white p-4 rounded-md mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-600 text-white p-4 rounded-md mb-6">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="p-6 text-gray-100 space-y-6">

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="name" class="label-style">{{ __('company-settings.name') }}</label>
                            <input disabled type="text" name="name" id="name"
                                   value="{{ old('name', $companySettings->name) }}" required
                                   class="input-style">
                        </div>

                        <div>
                            <label for="identification"
                                   class="label-style">{{ __('company-settings.identification') }}</label>
                            <input disabled type="text" name="identification" id="identification"
                                   value="{{ old('identification', $companySettings->identification) }}"
                                   class="input-style">
                        </div>

                        <div>
                            <label for="phone" class="label-style">{{ __('company-settings.phone') }}</label>
                            <input disabled type="text" name="phone" id="phone"
                                   value="{{ old('phone', $companySettings->phone) }}"
                                   class="input-style">
                        </div>

                        <div>
                            <label for="whatsapp_number"
                                   class="label-style">{{ __('company-settings.whatsapp_number') }}</label>
                            <input disabled type="text" name="whatsapp_number" id="whatsapp_number"
                                   value="{{ old('whatsapp_number', $companySettings->whatsapp_number) }}"
                                   class="input-style">
                        </div>

                        <div class="md:col-span-2">
                            <label for="whatsapp_webhook_url"
                                   class="label-style">{{ __('company-settings.whatsapp_webhook_url') }}</label>
                            <input disabled type="url" name="whatsapp_webhook_url" id="whatsapp_webhook_url"
                                   value="{{ old('whatsapp_webhook_url', $companySettings->whatsapp_webhook_url) }}"
                                   class="input-style">
                        </div>

                        <div>
                            <label for="default_language"
                                   class="label-style">{{ __('company-settings.default_language') }}</label>
                            <input disabled type="text" name="default_language" id="default_language"
                                   value="{{ old('default_language', $companySettings->default_language) }}"
                                   class="input-style">
                        </div>

                        <div>
                            <label for="timezone" class="label-style">{{ __('company-settings.timezone') }}</label>
                            <input disabled type="text" name="timezone" id="timezone"
                                   value="{{ old('timezone', $companySettings->timezone) }}"
                                   class="input-style">
                        </div>
                    </div>


                    <h4 class="mt-4 text-2xl dark:text-gray-300">
                        {{__('company-settings.business_hours')}}
                    </h4>
                    <hr class="dark:text-white mb-4">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="working_hour_start"
                                   class="label-style">{{ __('company-settings.working_hour_start') }}</label>
                            <input disabled type="time" name="working_hour_start" id="working_hour_start"
                                   value="{{ old('working_hour_start', $companySettings->working_hour_start) }}"
                                   class="input-style">
                        </div>

                        <div>
                            <label for="working_hour_end"
                                   class="label-style">{{ __('company-settings.working_hour_end') }}</label>
                            <input disabled type="time" name="working_hour_end" id="working_hour_end"
                                   value="{{ old('working_hour_end', $companySettings->working_hour_end) }}"
                                   class="input-style">
                        </div>

                        <div>
                            <label for="working_day_start"
                                   class="label-style">{{ __('company-settings.working_day_start') }}</label>
                            <select disabled name="working_day_start" id="working_day_start" class="input-style">
                                @foreach([1=>'Dom',2=>'Seg',3=>'Ter',4=>'Qua',5=>'Qui',6=>'Sex',7=>'Sáb'] as $key => $label)
                                    <option
                                        value="{{ $key }}" {{ old('working_day_start', $companySettings->working_day_start) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="working_day_end"
                                   class="label-style">{{ __('company-settings.working_day_end') }}</label>
                            <select disabled name="working_day_end" id="working_day_end" class="input-style">
                                @foreach([1=>'Dom',2=>'Seg',3=>'Ter',4=>'Qua',5=>'Qui',6=>'Sex',7=>'Sáb'] as $key => $label)
                                    <option
                                        value="{{ $key }}" {{ old('working_day_end', $companySettings->working_day_end) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2"
                             x-data="{ useAiChatbot: {{ $companySettings->use_ai_chatbot ? 'true' : 'false' }} }">
                            <label for="use_ai_chatbot" class="label-style block mb-2">
                                {{ __('company-settings.use_ai_chatbot') }}
                            </label>

                            <!-- Toggle Button (disabled) -->
                            <div :class="useAiChatbot ? 'bg-green-500' : 'bg-gray-300'"
                                 class="relative inline-flex items-center h-6 rounded-full w-11 opacity-50 cursor-not-allowed">
                                <span :class="useAiChatbot ? 'translate-x-6' : 'translate-x-1'"
                                      class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform">
                                </span>
                            </div>

                            <span class="ml-3 text-gray-300"
                                  x-text="useAiChatbot ? '{{ __('company-settings.active') }}' : '{{ __('company-settings.inactive') }}'"></span>
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('company-settings.edit', ['company_settings' => $companySettings->id]) }}"
                           class="save-button-style">
                            {{ __('company-settings.edit') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
