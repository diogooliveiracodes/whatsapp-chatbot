<x-app-layout>
    <x-global.header>
        {{ __('Cliente') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('customers.name') }}</label>
                            <p class="text-md text-gray-900 dark:text-gray-300">{{ $customer->name }}</p>
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('customers.phone') }}</label>
                            <p class="text-md text-gray-900 dark:text-gray-300">{{ \App\Helpers\PhoneHelper::format($customer->phone) }}</p>
                        </div>

                        <!-- Active Status -->
                        <div>
                            <label for="active" class="block text-sm font-medium text-gray-500 dark:text-gray-300">{{ __('customers.active') }}</label>
                            <p class="text-md text-gray-900 dark:text-gray-300">{{ $customer->active == 1 ? __('customers.yes') : __('customers.no') }}</p>
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
