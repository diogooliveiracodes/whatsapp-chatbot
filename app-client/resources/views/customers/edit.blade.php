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
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $customer->name) }}"
                                    class="input-style @error('name') border-red-500 @enderror"
                                    required
                                >
                                @error('name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Field -->
                            <div
                                x-data="{
                                    phone: '{{ old('phone', $customer->phone) }}',
                                    formatPhone() {
                                        let cleaned = this.phone.replace(/\D/g, '').substring(0, 11);
                                        let ddd = cleaned.substring(0, 2);
                                        let firstPart = '';
                                        let secondPart = '';

                                        if (cleaned.length >= 7) {
                                            if (cleaned.length === 11) {
                                                firstPart = cleaned.substring(2, 7);
                                                secondPart = cleaned.substring(7, 11);
                                            } else {
                                                firstPart = cleaned.substring(2, 6);
                                                secondPart = cleaned.substring(6, 10);
                                            }
                                        } else {
                                            firstPart = cleaned.substring(2);
                                        }

                                        return cleaned.length > 0
                                            ? `(${ddd}) ${firstPart}${secondPart ? '-' + secondPart : ''}`
                                            : '';
                                    }
                                }"
                            >
                                <label for="phone" class="label-style">{{ __('customers.phone') }}</label>
                                <input
                                    type="text"
                                    id="phone"
                                    name="phone"
                                    x-bind:value="formatPhone()"
                                    x-on:input="phone = $event.target.value"
                                    class="input-style @error('phone') border-red-500 @enderror"
                                    placeholder="(99) 99999-9999"
                                    required
                                >
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Active Toggle -->
                            <x-buttons.toggle-switch
                                name="active"
                                :label="__('fields.active')"
                                :value="old('active', $customer->active)"
                            />
                            @error('active')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex justify-between">
                            <x-cancel-link :href="route('customers.index')">
                                {{ __('customers.back') }}
                            </x-cancel-link>

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
