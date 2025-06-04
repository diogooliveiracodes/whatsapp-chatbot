<x-app-layout>
    <x-header>
        {{ __('unitSettings.title') }} - {{ $unitSettings->unit->name }}
    </x-header>

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
                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.name') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.phone') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->phone ?? '-' }}</p>
                        </div>

                        <!-- Address Information -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.street') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->street ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.number') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->number ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.complement') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->complement ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.neighborhood') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->neighborhood ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.city') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->city ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.state') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->state ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.zipcode') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->zipcode ?? '-' }}</p>
                        </div>

                        <!-- WhatsApp Configuration -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.whatsapp_webhook_url') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->whatsapp_webhook_url ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.whatsapp_number') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->whatsapp_number ?? '-' }}</p>
                        </div>

                        <!-- Working Hours -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.working_hour_start') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->working_hour_start ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.working_hour_end') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->working_hour_end ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.working_day_start') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->working_day_start ? __('unitSettings.days.' . $unitSettings->working_day_start) : '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.working_day_end') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->working_day_end ? __('unitSettings.days.' . $unitSettings->working_day_end) : '-' }}</p>
                        </div>

                        <!-- Additional Settings -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.use_ai_chatbot') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->use_ai_chatbot ? __('unitSettings.yes') : __('unitSettings.no') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.default_language') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->default_language ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">{{ __('unitSettings.timezone') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $unitSettings->timezone ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex justify-between">
                        <!-- Back Button -->
                        <x-cancel-link href="{{ route('units.show', $unitSettings->unit->id) }}">
                            {{ __('unitSettings.back') }}
                        </x-cancel-link>

                        <!-- Edit Button -->
                        <x-confirm-link href="{{ route('unitSettings.edit', $unitSettings->unit->id) }}">
                            {{ __('unitSettings.edit') }}
                        </x-confirm-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
