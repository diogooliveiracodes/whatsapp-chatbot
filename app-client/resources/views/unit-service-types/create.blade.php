<x-app-layout>
    <x-global.header>
        {{ __('actions.create') }} {{ __('pages.unitServiceTypes') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <x-global.session-alerts />

                    <form action="{{ route('unitServiceTypes.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <x-input-label for="unit_id" :value="__('unit-service-types.unit')" />
                                <select id="unit_id" name="unit_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required>
                                    <option value=""></option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('unit_id')" />
                            </div>

                            <div>
                                <x-input-label for="name" :value="__('fields.name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')"
                                    required />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="price" :value="__('fields.price')" />
                                <x-text-input id="price" name="price" type="text" class="mt-1 block w-full" :value="old('price')"
                                    required placeholder="R$ 0,00" />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('fields.description')" />
                                <x-global.textarea-input id="description" name="description" class="mt-1 block w-full">
                                    {{ old('description') }}
                                </x-global.textarea-input>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-buttons.toggle-switch name="active" :label="__('fields.active')" :value="old('active', true)" />
                            </div>

                            <div class="md:col-span-2">
                                <x-unit-service-types.week-days-checkboxes :weekDays="$weekDays" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <!-- Back Button -->
                            <x-cancel-link href="{{ route('unitServiceTypes.index') }}">
                                {{ __('actions.cancel') }}
                            </x-cancel-link>

                            <!-- Save Button -->
                            <x-primary-button type="submit">
                                {{ __('actions.save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('price');

            // Função para formatar o valor como moeda brasileira
            function formatCurrency(value) {
                // Remove tudo que não é dígito
                value = value.replace(/\D/g, '');

                // Converte para centavos
                value = (value / 100).toFixed(2) + '';

                // Aplica a formatação
                value = value.replace(".", ",");
                value = value.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");
                value = value.replace(/(\d)(\d{3}),/g, "$1.$2,");

                return 'R$ ' + value;
            }

            // Função para obter apenas o valor numérico
            function getNumericValue(value) {
                return value.replace(/\D/g, '') / 100;
            }

            priceInput.addEventListener('input', function() {
                let value = this.value;
                this.value = formatCurrency(value);
            });

            // Formatar valor inicial se existir (old values ou valores de edição)
            if (priceInput.value) {
                // Se o valor já está formatado como moeda, mantém
                if (!priceInput.value.includes('R$')) {
                    // Se é um valor numérico (old value), formata corretamente
                    let numericValue = parseFloat(priceInput.value);
                    if (!isNaN(numericValue)) {
                        // Multiplica por 100 para aplicar a formatação correta (centavos)
                        priceInput.value = formatCurrency((numericValue * 100).toString());
                    }
                }
            }

            // Antes de submeter o formulário, converter para valor numérico
            document.querySelector('form').addEventListener('submit', function() {
                if (priceInput.value) {
                    priceInput.value = getNumericValue(priceInput.value);
                }
            });
        });
    </script>
</x-app-layout>
