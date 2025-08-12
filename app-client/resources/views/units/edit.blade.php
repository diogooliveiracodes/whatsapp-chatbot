<x-app-layout>
    <x-global.header>
        {{ __('units.edit') }} - {{ $unit->name }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 lg:p-8 text-gray-900 dark:text-gray-100">
                    @if (session('error'))
                        <div class="mb-4 p-3 sm:p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800"
                            role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-4 p-3 sm:p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                            role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('units.update', $unit->id) }}" class="space-y-4 sm:space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('units.name')" />
                            <x-text-input id="name" name="name" type="text"
                                class="mt-1 block w-full text-sm sm:text-base" :value="old('name', $unit->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-buttons.toggle-switch name="active" :label="__('fields.active')" :value="old('active', $unit->active)" />
                        </div>

                        <div class="mt-6 flex flex-col sm:flex-row justify-between gap-4 sm:gap-0">
                            <!-- Back Button -->
                            <x-cancel-link href="{{ route('units.index') }}"
                                class="order-2 sm:order-1 text-center sm:text-left">
                                {{ __('units.back') }}
                            </x-cancel-link>

                            <!-- Update Button -->
                            <x-primary-button type="submit" class="order-1 sm:order-2 w-full sm:w-auto">
                                {{ __('units.update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
