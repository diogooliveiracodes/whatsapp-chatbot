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
                                <label for="name" class="label-style">Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}"
                                       class="input-style"
                                       required>
                            </div>

                            <!-- Phone Field -->
                            <div>
                                <label for="phone" class="label-style">Phone</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}"
                                       class="input-style"
                                       required>
                            </div>

                            <!-- Document Number -->
                            <div>
                                <label for="document_number" class="label-style">Document
                                    Number</label>
                                <input type="text" id="document_number" name="document_number"
                                       value="{{ old('document_number', $customer->document_number) }}"
                                       class="input-style"
                                       required>
                            </div>

                            <!-- Prospect Origin -->
                            <div>
                                <label for="prospect_origin" class="label-style">Prospect
                                    Origin</label>
                                <input type="text" id="prospect_origin" name="prospect_origin"
                                       value="{{ old('prospect_origin', $customer->prospect_origin) }}"
                                       class="input-style"
                                       required>
                            </div>

                            <!-- Active Status -->
                            <div>
                                <label for="active" class="label-style">Active</label>
                                <div class="mt-1">
                                    <label class="inline-flex items-center mr-4">
                                        <input type="radio" id="active_yes" name="active" value="1"
                                               {{ old('active', $customer->active) == 1 ? 'checked' : '' }}
                                               class="form-radio text-indigo-500">
                                        <span class="ml-2 dark:text-gray-300">Yes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" id="active_no" name="active" value="0"
                                               {{ old('active', $customer->active) == 0 ? 'checked' : '' }}
                                               class="form-radio text-indigo-500">
                                        <span class="ml-2 dark:text-gray-300">No</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="type" class="label-style">Type</label>
                                <div class="mt-1">
                                    <label class="inline-flex items-center mr-4">
                                        <input type="radio" id="type_cpf" name="type" value="1"
                                               {{ old('type', $customer->type) == 1 ? 'checked' : '' }}
                                               class="form-radio text-indigo-500">
                                        <span class="ml-2 dark:text-gray-300">CPF</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" id="type_cnpj" name="type" value="2"
                                               {{ old('type', $customer->type) == 2 ? 'checked' : '' }}
                                               class="form-radio text-indigo-500">
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
                                <label for="street" class="label-style">Street</label>
                                <input type="text" id="street" name="street"
                                       value="{{ old('street', $customer->street) }}"
                                       class="input-style"
                                       placeholder="Street" required>
                            </div>

                            <!-- Number -->
                            <div>
                                <label for="number" class="label-style">Number</label>
                                <input type="text" id="number" name="number"
                                       value="{{ old('number', $customer->number) }}"
                                       class="input-style"
                                       placeholder="Number" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3 pt-4">
                            <!-- Neighborhood -->
                            <div>
                                <label for="neighborhood"
                                       class="label-style">Neighborhood</label>
                                <input type="text" id="neighborhood" name="neighborhood"
                                       value="{{ old('neighborhood', $customer->neighborhood) }}"
                                       class="input-style"
                                       placeholder="Neighborhood" required>
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="label-style">City</label>
                                <input type="text" id="city" name="city" value="{{ old('city', $customer->city) }}"
                                       class="input-style"
                                       placeholder="City" required>
                            </div>

                            <!-- State -->
                            <div>
                                <label for="state" class="label-style">State</label>
                                <input type="text" id="state" name="state" value="{{ old('state', $customer->state) }}"
                                       class="input-style"
                                       placeholder="State" required>
                            </div>
                        </div>


                        <!-- Action Buttons -->
                        <div class="mt-6 flex justify-between">
                            <!-- Back Button -->
                            <x-cancel-link :href="route('customers.index')">
                                Back
                            </x-cancel-link>

                            <!-- Save Button -->
                            <x-primary-button type="submit">
                                Save Changes
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
