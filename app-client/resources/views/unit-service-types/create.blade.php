<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('actions.create') }} {{ __('pages.unitServiceTypes') }}
        </h2>
    </x-slot>

    <x-global.content-card>
        <form action="{{ route('unitServiceTypes.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <x-input-label for="unit_id" :value="__('unit-service-types.unit')" />
                <select id="unit_id" name="unit_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    <option value=""></option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('unit_id')" />
            </div>

            <div class="mb-4">
                <x-input-label for="name" :value="__('fields.name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div class="mb-4">
                <x-input-label for="description" :value="__('fields.description')" />
                <x-global.textarea-input id="description" name="description" class="mt-1 block w-full">
                    {{ old('description') }}
                </x-global.textarea-input>
                <x-input-error class="mt-2" :messages="$errors->get('description')" />
            </div>

            <div class="mt-6 flex justify-between">
                <!-- Back Button -->
                <x-cancel-link href="{{ route('unitServiceTypes.index') }}">
                    {{ __('actions.cancel') }}
                </x-cancel-link>

                <!-- Save Button -->
                <x-primary-button type="submit">{{ __('actions.save') }}</x-primary-button>
            </div>
        </form>
    </x-global.content-card>
</x-app-layout>
