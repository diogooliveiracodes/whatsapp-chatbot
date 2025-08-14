<x-app-layout>
    <x-global.header>
        {{ __('signature.payment_title') }}
    </x-global.header>

    <x-global.content-card title="{{ __('signature.payment_details') }}">
        <div class="space-y-6">
            <!-- Informações do Pagamento -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('signature.payment_summary') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Plano -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('signature.plan_name') }}
                        </h4>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $signature->plan->name }}
                        </p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            {{ $signature->plan->description }}
                        </p>
                    </div>

                    <!-- Valor -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('signature.payment_amount') }}
                        </h4>
                        <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">
                            R$ {{ number_format($signature->plan->price, 2, ',', '.') }}
                        </p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            {{ __('signature.per_month') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Código PIX -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('signature.pix_payment') }}
                </h3>

                <div class="space-y-4">
                    <!-- Instruções -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    {{ __('signature.pix_instructions_title') }}
                                </h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>{{ __('signature.pix_instruction_1') }}</li>
                                        <li>{{ __('signature.pix_instruction_2') }}</li>
                                        <li>{{ __('signature.pix_instruction_3') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Código PIX -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <div class="text-center">
                            <!-- Spinner de Carregamento -->
                            <div id="pixLoading" class="mb-4">
                                <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-green-500 hover:bg-green-400 transition ease-in-out duration-150 cursor-not-allowed">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ __('signature.generating_pix_code') }}
                                </div>
                            </div>

                            <!-- QR Code e Código PIX -->
                            <div id="pixContent" class="hidden">
                                <!-- QR Code -->
                                <div class="mb-6">
                                    <div class="bg-white p-4 rounded-lg inline-block">
                                        <div id="qrcode" class="w-48 h-48"></div>
                                    </div>
                                </div>

                                <!-- Código PIX -->
                                <div class="mb-6">
                                    <label for="pixCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('signature.pix_code') }}
                                    </label>
                                    <div class="flex">
                                        <input type="text" id="pixCode" readonly
                                            class="flex-1 rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring-green-500"
                                            value="">
                                        <button onclick="copyPixCode()"
                                            class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 dark:border-gray-600 rounded-r-md bg-gray-50 dark:bg-gray-600 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-500 focus:z-10 focus:border-green-500 focus:ring-green-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Status do Pagamento -->
                                <div class="mb-6">
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                                    {{ __('signature.payment_pending') }}
                                                </h3>
                                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                                    <p>{{ __('signature.payment_pending_message') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botões de Ação -->
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <button onclick="checkPaymentStatus()"
                                        class="inline-flex items-center px-6 py-3 bg-blue-500 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        {{ __('signature.check_payment_status') }}
                                    </button>

                                    <a href="{{ route('signature.index') }}"
                                        class="inline-flex items-center px-6 py-3 bg-gray-500 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                        {{ __('signature.back_to_signature') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-global.content-card>
</x-app-layout>

<!-- QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<script>
    let paymentCheckInterval;

    // Função para gerar o código PIX
    async function generatePixCode() {
        try {
            const response = await fetch('{{ route("signature.generate-pix", $signature->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (!response.ok) {
                throw new Error('Erro ao gerar código PIX');
            }

            const data = await response.json();

            if (data.success) {
                // Esconder spinner e mostrar conteúdo
                document.getElementById('pixLoading').classList.add('hidden');
                document.getElementById('pixContent').classList.remove('hidden');

                // Preencher código PIX
                document.getElementById('pixCode').value = data.pix_code;

                // Gerar QR Code
                QRCode.toCanvas(document.getElementById('qrcode'), data.pix_code, {
                    width: 192,
                    margin: 2,
                    color: {
                        dark: '#000000',
                        light: '#FFFFFF'
                    }
                }, function (error) {
                    if (error) {
                        console.error('Erro ao gerar QR Code:', error);
                    }
                });

                // Iniciar verificação de status
                startPaymentStatusCheck();
            } else {
                throw new Error(data.message || 'Erro ao gerar código PIX');
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao gerar código PIX. Tente novamente.');
        }
    }

    // Função para copiar código PIX
    function copyPixCode() {
        const pixCode = document.getElementById('pixCode');
        pixCode.select();
        pixCode.setSelectionRange(0, 99999); // Para dispositivos móveis

        try {
            document.execCommand('copy');
            alert('{{ __("signature.pix_code_copied") }}');
        } catch (err) {
            console.error('Erro ao copiar:', err);
        }
    }

    // Função para verificar status do pagamento
    async function checkPaymentStatus() {
        try {
            const response = await fetch('{{ route("signature.check-payment", $signature->id) }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (!response.ok) {
                throw new Error('Erro ao verificar status do pagamento');
            }

            const data = await response.json();

            if (data.status === 'paid') {
                alert('{{ __("signature.payment_confirmed") }}');
                window.location.href = '{{ route("signature.index") }}';
            } else if (data.status === 'pending') {
                alert('{{ __("signature.payment_still_pending") }}');
            } else {
                alert('{{ __("signature.payment_not_found") }}');
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao verificar status do pagamento. Tente novamente.');
        }
    }

    // Função para iniciar verificação automática de status
    function startPaymentStatusCheck() {
        // Verificar a cada 30 segundos
        paymentCheckInterval = setInterval(async () => {
            try {
                const response = await fetch('{{ route("signature.check-payment", $signature->id) }}', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    const data = await response.json();

                    if (data.status === 'paid') {
                        clearInterval(paymentCheckInterval);
                        alert('{{ __("signature.payment_confirmed") }}');
                        window.location.href = '{{ route("signature.index") }}';
                    }
                }
            } catch (error) {
                console.error('Erro na verificação automática:', error);
            }
        }, 30000); // 30 segundos
    }

    // Gerar código PIX quando a página carregar
    document.addEventListener('DOMContentLoaded', function() {
        generatePixCode();
    });

    // Limpar intervalo quando a página for fechada
    window.addEventListener('beforeunload', function() {
        if (paymentCheckInterval) {
            clearInterval(paymentCheckInterval);
        }
    });
</script>
