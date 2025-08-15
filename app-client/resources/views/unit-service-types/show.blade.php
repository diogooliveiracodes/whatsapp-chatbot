<x-app-layout>
    <x-global.header>
        {{ __('pages.unitServiceType') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('fields.name') }}</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $unitServiceType->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('fields.description') }}</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $unitServiceType->description ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('unit-service-types.unitName') }}</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $unitServiceType->unit?->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('fields.price') }}</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">R$ {{ number_format($unitServiceType->price, 2, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('fields.created_at') }}</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($unitServiceType->created_at)->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('fields.updated_at') }}</label>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($unitServiceType->updated_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <!-- Back Button -->
                        <x-cancel-link href="{{ route('unitServiceTypes.index') }}">
                            {{ __('actions.cancel') }}
                        </x-cancel-link>

                        <!-- Edit Button -->
                        <x-confirm-link href="{{ route('unitServiceTypes.edit', $unitServiceType->id) }}">
                            {{ __('actions.edit') }}
                        </x-confirm-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
