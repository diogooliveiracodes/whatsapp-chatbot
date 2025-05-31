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
                            <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
                            <p class="text-md dark:text-gray-300">{{ $customer->name }}</p>
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300">Phone</label>
                            <p class="text-md dark:text-gray-300">{{ $customer->phone }}</p>
                        </div>

                        <!-- Document Number -->
                        <div>
                            <label for="document_number" class="block text-sm font-medium text-gray-300">Document
                                Number</label>
                            <p class="text-md dark:text-gray-300">{{ $customer->document_number }}</p>
                        </div>

                        <!-- Prospect Origin -->
                        <div>
                            <label for="prospect_origin" class="block text-sm font-medium text-gray-300">Prospect
                                Origin</label>
                            <p class="text-md dark:text-gray-300">{{ $customer->prospect_origin }}</p>
                        </div>

                        <!-- Active Status -->
                        <div>
                            <label for="active" class="block text-sm font-medium text-gray-300">Active</label>
                            <p class="text-md dark:text-gray-300">{{ $customer->active == 1 ? 'Yes' : 'No' }}</p>
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-300">Type</label>
                            <p class="text-md dark:text-gray-300">{{ $customer->type == 1 ? 'CPF' : 'CNPJ' }}</p>
                        </div>
                    </div>

                    <h4 class="mt-4 text-2xl dark:text-gray-300">
                        Address
                    </h4>
                    <hr class="dark:text-white">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Street -->
                        <div>
                            <label for="street" class="block text-sm font-medium text-gray-300">Street</label>
                            <p class="text-md dark:text-gray-300">{{ $customer->street }}</p>
                        </div>

                        <!-- Number -->
                        <div>
                            <label for="number" class="block text-sm font-medium text-gray-300">Number</label>
                            <p class="text-md dark:text-gray-300">{{ $customer->number }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <!-- Neighborhood -->
                        <div>
                            <label for="neighborhood"
                                   class="block text-sm font-medium text-gray-300">Neighborhood</label>
                            <p class="text-md dark:text-gray-300">{{ $customer->neighborhood }}</p>
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-300">City</label>
                            <p class="text-md dark:text-gray-300">{{ $customer->city }}</p>
                        </div>

                        <!-- State -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-300">State</label>
                            <p class="text-md dark:text-gray-300">{{ $customer->state }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex justify-between">
                        <!-- Back Button -->
                        <x-cancel-link href="{{ route('customers.index') }}">
                            Back
                        </x-cancel-link>

                        <!-- Edit Button -->
                        <x-confirm-link href="{{ route('customers.edit', $customer->id) }}">
                            Edit
                        </x-confirm-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
