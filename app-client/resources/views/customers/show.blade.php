<x-app-layout>
    <x-header>
        {{ __('Cliente') }}
    </x-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-100 space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300">{{ __('customers.name') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $customer->name }}</p>
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300">{{ __('customers.phone') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $customer->phone }}</p>
                        </div>

                        <!-- Active Status -->
                        <div>
                            <label for="active" class="block text-sm font-medium text-gray-300">{{ __('customers.active') }}</label>
                            <p class="text-md dark:text-gray-300">{{ $customer->active == 1 ? __('customers.yes') : __('customers.no') }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex justify-between">
                        <!-- Back Button -->
                        <x-cancel-link href="{{ route('customers.index') }}">
                            {{ __('customers.back') }}
                        </x-cancel-link>

                        <!-- Edit Button -->
                        <x-confirm-link href="{{ route('customers.edit', $customer->id) }}">
                            {{ __('customers.edit') }}
                        </x-confirm-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
