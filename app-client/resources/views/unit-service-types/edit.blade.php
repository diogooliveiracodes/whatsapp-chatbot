<x-app-layout>
    <x-global.header>
        {{ __('actions.edit') }} {{ __('pages.unitServiceTypes') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <x-global.session-alerts />

                    <form action="{{ route('unitServiceTypes.update', $unitServiceType) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <x-input-label for="name" :value="__('fields.name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $unitServiceType->name)"
                                    required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="price" :value="__('fields.price')" />
                                <x-text-input id="price" name="price" type="text" class="mt-1 block w-full" :value="old('price', $unitServiceType->price)"
                                    required placeholder="R$ 0,00" />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('fields.description')" />
                                <x-global.textarea-input id="description" name="description" class="mt-1 block w-full">
                                    {{ old('description', $unitServiceType->description) }}
                                </x-global.textarea-input>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-buttons.toggle-switch name="active" :label="__('fields.active')" :value="old('active', $unitServiceType->active)" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <!-- Back Button -->
                            <x-cancel-link href="{{ route('unitServiceTypes.index') }}">
                                {{ __('actions.cancel') }}
                            </x-cancel-link>

                            <!-- Update Button -->
                            <x-primary-button type="submit">{{ __('actions.save') }}</x-primary-button>
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
