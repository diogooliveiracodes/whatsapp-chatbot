<x-admin-layout>
    <x-global.header>
        {{ __('companies.edit') }}
    </x-global.header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-100 space-y-6">
                    <x-global.session-alerts />

                    <form action="{{ route('admin.companies.update', $company) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Name Field -->
                            <div>
                                <label for="name" class="label-style">{{ __('companies.name') }}</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $company->name) }}"
                                    class="input-style @error('name') border-red-500 @enderror"
                                    required
                                >
                                @error('name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Document Type Field -->
                            <div>
                                <label for="document_type" class="label-style">{{ __('companies.document_type') }}</label>
                                <select
                                    id="document_type"
                                    name="document_type"
                                    class="input-style @error('document_type') border-red-500 @enderror"
                                >
                                    <option value="">{{ __('companies.select_type') }}</option>
                                    <option value="1" {{ old('document_type', $company->document_type) == '1' ? 'selected' : '' }}>
                                        {{ __('companies.cnpj') }}
                                    </option>
                                    <option value="2" {{ old('document_type', $company->document_type) == '2' ? 'selected' : '' }}>
                                        {{ __('companies.cpf') }}
                                    </option>
                                </select>
                                @error('document_type')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Document Number Field -->
                            <div>
                                <label for="document_number" class="label-style">{{ __('companies.document_number') }}</label>
                                <input
                                    required
                                    type="text"
                                    id="document_number"
                                    name="document_number"
                                    value="{{ old('document_number', $company->document_number) }}"
                                    class="input-style @error('document_number') border-red-500 @enderror"
                                    placeholder="{{ __('companies.document_placeholder') }}"
                                >
                                @error('document_number')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Active Toggle -->
                            <x-buttons.toggle-switch
                                name="active"
                                :label="__('companies.active')"
                                :value="old('active', $company->active)"
                            />
                            @error('active')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex justify-between">
                            <x-cancel-link :href="route('admin.companies.index')">
                                {{ __('companies.back') }}
                            </x-cancel-link>

                            <x-primary-button type="submit">
                                {{ __('actions.save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
