<x-app-layout>
    <x-global.header>
        {{ __('customers.create') }}
    </x-global.header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-100 space-y-6">
                    <x-global.session-alerts />

                    <form action="{{ route('customers.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Name Field -->
                            <div>
                                <label for="name" class="label-style">{{ __('customers.name') }}</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                       class="input-style"
                                       required>
                            </div>

                            <!-- Phone Field -->
                            <div>
                                <label for="phone" class="label-style">{{ __('customers.phone') }}</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                       class="input-style"
                                       required>
                            </div>

                            <!-- Active Status -->
                            <x-buttons.toggle-switch
                                name="active"
                                :label="__('fields.active')"
                            />
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex justify-between">
                            <!-- Back Button -->
                            <x-cancel-link :href="route('customers.index')">
                                {{ __('customers.back') }}
                            </x-cancel-link>

                            <!-- Save Button -->
                            <x-primary-button type="submit">
                                {{ __('customers.create') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
