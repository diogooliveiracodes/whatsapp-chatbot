<x-app-layout>
    <x-header>
        {{ __('Editar Cliente') }}
    </x-header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-100 space-y-6">
                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="bg-red-600 text-white p-4 rounded-md mb-6">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Name Field -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       required>
                            </div>

                            <!-- Phone Field -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-300">Phone</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       required>
                            </div>

                            <!-- Document Number -->
                            <div>
                                <label for="document_number" class="block text-sm font-medium text-gray-300">Document
                                    Number</label>
                                <input type="text" id="document_number" name="document_number"
                                       value="{{ old('document_number', $customer->document_number) }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       required>
                            </div>

                            <!-- Prospect Origin -->
                            <div>
                                <label for="prospect_origin" class="block text-sm font-medium text-gray-300">Prospect
                                    Origin</label>
                                <input type="text" id="prospect_origin" name="prospect_origin"
                                       value="{{ old('prospect_origin', $customer->prospect_origin) }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       required>
                            </div>

                            <!-- Active Status -->
                            <div>
                                <label for="active" class="block text-sm font-medium text-gray-300">Active</label>
                                <div class="mt-1">
                                    <label class="inline-flex items-center mr-4">
                                        <input type="radio" id="active_yes" name="active" value="1"
                                               {{ old('active', $customer->active) == 1 ? 'checked' : '' }} class="form-radio text-indigo-500">
                                        <span class="ml-2 dark:text-gray-300">Yes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" id="active_no" name="active" value="0"
                                               {{ old('active', $customer->active) == 0 ? 'checked' : '' }} class="form-radio text-indigo-500">
                                        <span class="ml-2 dark:text-gray-300">No</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-300">Type</label>
                                <div class="mt-1">
                                    <label class="inline-flex items-center mr-4">
                                        <input type="radio" id="type_cpf" name="type" value="1"
                                               {{ old('type', $customer->type) == 1 ? 'checked' : '' }} class="form-radio text-indigo-500">
                                        <span class="ml-2 dark:text-gray-300">CPF</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" id="type_cnpj" name="type" value="2"
                                               {{ old('type', $customer->type) == 2 ? 'checked' : '' }} class="form-radio text-indigo-500">
                                        <span class="ml-2 dark:text-gray-300">CNPJ</span>
                                    </label>
                                </div>
                            </div>


                        </div>

                        <h4 class="mt-4 text-2xl dark:text-gray-300">
                            Adress
                        </h4>
                        <hr class="dark:text-white">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 pt-4">
                            <!-- Street -->
                            <div>
                                <label for="street" class="block text-sm font-medium text-gray-300">Street</label>
                                <input type="text" id="street" name="street"
                                       value="{{ old('street', $customer->street) }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Street" required>
                            </div>

                            <!-- Number -->
                            <div>
                                <label for="number" class="block text-sm font-medium text-gray-300">Number</label>
                                <input type="text" id="number" name="number"
                                       value="{{ old('number', $customer->number) }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Number" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3 pt-4">
                            <!-- Neighborhood -->
                            <div>
                                <label for="neighborhood"
                                       class="block text-sm font-medium text-gray-300">Neighborhood</label>
                                <input type="text" id="neighborhood" name="neighborhood"
                                       value="{{ old('neighborhood', $customer->neighborhood) }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Neighborhood" required>
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-300">City</label>
                                <input type="text" id="city" name="city" value="{{ old('city', $customer->city) }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="City" required>
                            </div>

                            <!-- State -->
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-300">State</label>
                                <input type="text" id="state" name="state" value="{{ old('state', $customer->state) }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="State" required>
                            </div>
                        </div>


                        <!-- Action Buttons -->
                        <div class="mt-6 flex justify-between">
                            <!-- Back Button -->
                            <a href="{{ route('customers.index') }}"
                               class="inline-block px-4 py-2 text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Back
                            </a>

                            <!-- Save Button -->
                            <button type="submit"
                                    class="inline-block px-4 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
