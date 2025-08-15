<x-app-layout>
    <x-global.header>
        {{ __('units.edit') }} - {{ $unit->name }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <x-global.session-alerts />

                    <form method="POST" action="{{ route('units.update', $unit->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="name" :value="__('units.name')" />
                                <x-text-input id="name" name="name" type="text"
                                    class="mt-1 block w-full" :value="old('name', $unit->name)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-buttons.toggle-switch name="active" :label="__('fields.active')" :value="old('active', $unit->active)" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <!-- Back Button -->
                            <x-cancel-link href="{{ route('units.index') }}">
                                {{ __('units.back') }}
                            </x-cancel-link>

                            <!-- Update Button -->
                            <x-primary-button type="submit">
                                {{ __('units.update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
