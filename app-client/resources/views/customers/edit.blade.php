<x-app-layout>
    <x-global.header>
        {{ __('customers.edit') }}
    </x-global.header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-100 space-y-6">
                    <x-global.session-alerts />

                    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Name Field -->
                            <div>
                                <label for="name" class="label-style">{{ __('customers.name') }}</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}"
                                       class="input-style"
                                       required>
                            </div>

                            <!-- Phone Field -->
                            <div>
                                <label for="phone" class="label-style">{{ __('customers.phone') }}</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}"
                                       class="input-style"
                                       required>
                            </div>

                            <!-- Active Status -->
                            <div>
                                <label for="active" class="label-style">{{ __('customers.active') }}</label>
                                <div class="mt-1">
                                    <label class="inline-flex items-center mr-4">
                                        <input type="radio" id="active_yes" name="active" value="1"
                                               {{ old('active', $customer->active) == 1 ? 'checked' : '' }}
                                               class="form-radio text-indigo-500">
                                        <span class="ml-2 dark:text-gray-300">{{ __('customers.yes') }}</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" id="active_no" name="active" value="0"
                                               {{ old('active', $customer->active) == 0 ? 'checked' : '' }}
                                               class="form-radio text-indigo-500">
                                        <span class="ml-2 dark:text-gray-300">{{ __('customers.no') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex justify-between">
                            <!-- Back Button -->
                            <x-cancel-link :href="route('customers.index')">
                                {{ __('customers.back') }}
                            </x-cancel-link>

                            <!-- Save Button -->
                            <x-primary-button type="submit">
                                {{ __('customers.save_changes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
