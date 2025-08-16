<x-app-layout>
    <x-global.header>
        {{ __('units.details') }} - {{ $unit->name }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <x-global.session-alerts />

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('units.details') }}</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('units.name') }}</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $unit->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('units.active') }}</label>
                                    <div class="mt-1">
                                        <label class="relative inline-flex items-center cursor-not-allowed">
                                            <input type="checkbox" class="sr-only peer" disabled
                                                {{ $unit->active ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600 opacity-75">
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('units.created_at') }}</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($unit->created_at)->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('units.updated_at') }}</label>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($unit->updated_at)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Layout -->
                    <div class="mt-6 flex flex-col gap-4 sm:hidden">
                        <x-buttons.settings-secondary :route="route('unitSettings.show', $unit->unitSettings->id)" class="w-full" />
                        <x-confirm-link class="w-full text-center" href="{{ route('units.edit', $unit->id) }}">
                            {{ __('units.edit') }}
                        </x-confirm-link>
                        <x-cancel-link href="{{ route('units.index') }}" class="text-center">
                            {{ __('units.back') }}
                        </x-cancel-link>
                    </div>

                    <!-- Desktop Layout -->
                    <div class="mt-6 hidden sm:flex justify-between">
                        <x-cancel-link href="{{ route('units.index') }}">
                            {{ __('units.back') }}
                        </x-cancel-link>

                        <div class="flex gap-2">
                            <x-buttons.settings-secondary :route="route('unitSettings.show', $unit->unitSettings->id)" />
                            <x-confirm-link href="{{ route('units.edit', $unit->id) }}" class="text-center">
                                {{ __('units.edit') }}
                            </x-confirm-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
