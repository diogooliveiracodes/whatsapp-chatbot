<x-app-layout>
    <x-global.header>
        {{ __('automated-messages.new_automated_message') }}
    </x-global.header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gray-800 border-b border-gray-700">
                    <x-global.session-alerts />

                    <!-- Unit selector for owners -->
                    @if($showUnitSelector)
                        <div class="mb-6">
                            <label for="unit-selector" class="block text-sm font-medium text-gray-300 mb-2">
                                {{ __('automated-messages.unit_selection') }}
                            </label>
                            <select id="unit-selector"
                                    class="block w-full max-w-xs px-3 py-2 border border-gray-600 bg-gray-700 text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    onchange="changeUnit(this.value)">
                                @foreach($units as $unitOption)
                                    <option value="{{ $unitOption->id }}" {{ $selectedUnit->id == $unitOption->id ? 'selected' : '' }}>
                                        {{ $unitOption->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('automated-messages.store') }}" class="space-y-6">
                        @csrf

                        <!-- Hidden field for unit_id -->
                        <input type="hidden" name="unit_id" value="{{ $selectedUnit->id }}" />

                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-300">
                                {{ __('automated-messages.fields.name') }}
                            </label>
                            <input id="name" type="text" name="name"
                                value="{{ old('name') }}"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('name') border-red-500 @enderror"
                                placeholder="{{ __('automated-messages.placeholders.name') }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label for="type" class="block font-medium text-sm text-gray-300">
                                {{ __('automated-messages.fields.type') }}
                            </label>
                            <select id="type" name="type"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('type') border-red-500 @enderror"
                                required>
                                <option value="">{{ __('automated-messages.placeholders.name') }}</option>
                                @foreach ($messageTypes as $type)
                                    <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>
                                        {{ $type->getLabel() }} - {{ $type->getDescription() }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div>
                            <label for="content" class="block font-medium text-sm text-gray-300">
                                {{ __('automated-messages.fields.content') }}
                            </label>
                            <textarea id="content" name="content" rows="6" maxlength="1000"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('content') border-red-500 @enderror"
                                placeholder="{{ __('automated-messages.placeholders.content') }}" required oninput="updateCharCount(this)">{{ old('content') }}</textarea>
                            <div class="flex justify-between items-center mt-1">
                                <x-input-error :messages="$errors->get('content')" class="mt-2" />
                                <span id="char-count" class="text-xs text-gray-400">0/1000</span>
                            </div>

                            <!-- Variables help -->
                            <div class="mt-2 p-3 bg-blue-900/20 border border-blue-800 rounded-lg">
                                <h4 class="text-sm font-medium text-blue-300 mb-2">{{ __('automated-messages.variables.title') }}</h4>
                                <p class="text-xs text-blue-400 mb-2">{{ __('automated-messages.variables.description') }}</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 text-xs text-blue-300">
                                    <div>{{ __('automated-messages.variables.customer_name') }}</div>
                                    <div>{{ __('automated-messages.variables.customer_phone') }}</div>
                                    <div>{{ __('automated-messages.variables.schedule_date') }}</div>
                                    <div>{{ __('automated-messages.variables.schedule_time') }}</div>
                                    <div>{{ __('automated-messages.variables.service_name') }}</div>
                                    <div>{{ __('automated-messages.variables.unit_name') }}</div>
                                    <div>{{ __('automated-messages.variables.company_name') }}</div>
                                    <div>{{ __('automated-messages.variables.payment_amount') }}</div>
                                    <div>{{ __('automated-messages.variables.payment_method') }}</div>
                                </div>
                            </div>
                        </div>



                        <!-- Action Buttons -->
                        <div class="mt-6 flex justify-between">
                            <!-- Back Button -->
                            <x-cancel-link :href="route('automated-messages.index')">
                                {{ __('actions.back') }}
                            </x-cancel-link>

                            <!-- Create Button -->
                            <x-primary-button type="submit">
                                {{ __('automated-messages.create') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateCharCount(textarea) {
            const charCount = document.getElementById('char-count');
            const currentLength = textarea.value.length;
            const maxLength = 1000;

            charCount.textContent = `${currentLength}/${maxLength}`;

            if (currentLength > maxLength * 0.9) {
                charCount.classList.add('text-yellow-400');
                charCount.classList.remove('text-gray-400', 'text-red-400');
            } else if (currentLength >= maxLength) {
                charCount.classList.add('text-red-400');
                charCount.classList.remove('text-gray-400', 'text-yellow-400');
            } else {
                charCount.classList.add('text-gray-400');
                charCount.classList.remove('text-yellow-400', 'text-red-400');
            }
        }

        // Initialize character count on page load
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('content');
            if (textarea) {
                updateCharCount(textarea);
            }
        });
    </script>

    @if($showUnitSelector)
        <script>
            function changeUnit(unitId) {
                window.location.href = '{{ route('automated-messages.create') }}?unit_id=' + unitId;
            }
        </script>
    @endif
</x-app-layout>
