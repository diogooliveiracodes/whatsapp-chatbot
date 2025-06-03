<x-app-layout>
    <x-header>
        {{ __('units.details') }} - {{ $unit->name }}
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('units.details') }}</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('units.name') }}</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $unit->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('units.active') }}</label>
                                    <div class="mt-1">
                                        <label class="relative inline-flex items-center cursor-not-allowed">
                                            <input type="checkbox" class="sr-only peer" disabled {{ $unit->active ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600 opacity-75"></div>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('units.created_at') }}</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($unit->created_at)->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('units.updated_at') }}</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($unit->updated_at)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <!-- Back Button -->
                        <x-cancel-link href="{{ route('units.index') }}">
                            {{ __('units.back') }}
                        </x-cancel-link>

                        <!-- Edit Button -->
                        <x-confirm-link href="{{ route('units.edit', $unit->id) }}">
                            {{ __('units.edit') }}
                        </x-confirm-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
