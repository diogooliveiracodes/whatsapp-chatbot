<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('actions.edit') }} {{ __('pages.unitServiceTypes') }}
        </h2>
    </x-slot>

    <x-global.content-card>
        <form action="{{ route('unitServiceTypes.update', $unitServiceType) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <x-input-label for="name" :value="__('fields.name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                    :value="old('name', $unitServiceType->name)" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
            <div class="mb-4">
                <x-input-label for="description" :value="__('fields.description')" />
                <x-global.textarea-input id="description" name="description" class="mt-1 block w-full">
                    {{ old('description', $unitServiceType->description) }}
                </x-global.textarea-input>
                <x-input-error class="mt-2" :messages="$errors->get('description')" />
            </div>

            <x-buttons.toggle-switch
                name="active"
                :label="__('fields.active')"
                :value="old('active', $unitServiceType->active)"
            />

            <div class="mt-6 flex justify-between">
                <!-- Back Button -->
                <x-cancel-link href="{{ route('unitServiceTypes.index') }}">
                    {{ __('actions.cancel') }}
                </x-cancel-link>

                <!-- Update Button -->
                <x-primary-button type="submit">{{ __('actions.save') }}</x-primary-button>
            </div>
        </form>
    </x-global.content-card>
</x-app-layout>
