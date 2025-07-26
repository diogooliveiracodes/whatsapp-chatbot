<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('pages.unitServiceType') }}
        </h2>
    </x-slot>

    <x-global.content-card>
        <div class="space-y-6">
            <div class="mb-4">
                <x-input-label for="name" :value="__('fields.name')" />
                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $unitServiceType->name }}</p>
            </div>

            <div class="mb-4">
                <x-input-label for="description" :value="__('fields.description')" />
                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $unitServiceType->description ?? '-' }}</p>
            </div>

            <div class="mb-4">
                <x-input-label for="unit" :value="__('unit-service-types.unitName')" />
                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $unitServiceType->unit?->name ?? '-' }}</p>
            </div>

            <div class="mb-4">
                <x-input-label for="price" :value="__('fields.price')" />
                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $unitServiceType->price }}</p>
            </div>

            <div class="mb-4">
                <x-input-label for="created_at" :value="__('fields.created_at')" />
                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($unitServiceType->created_at)->format('d/m/Y H:i') }}</p>
            </div>

            <div class="mb-4">
                <x-input-label for="updated_at" :value="__('fields.updated_at')" />
                <p class="mt-1 text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($unitServiceType->updated_at)->format('d/m/Y H:i') }}</p>
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
    </x-global.content-card>
</x-app-layout>
