<x-app-layout>
    <x-global.header>
        {{ __('signature.payment_title') }}
    </x-global.header>

    <x-global.content-card>
        <x-buttons.back-button
            :route="route('signature.index')"
            :text="__('signature.back_to_signature')"
        />

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
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
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
                            <!-- Botão para Gerar Código PIX -->
                            <div id="pixGenerateButton" class="mb-6">
                                <button onclick="generatePixCode()"
                                    class="inline-flex items-center px-6 py-3 bg-green-500 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                        </path>
                                    </svg>
                                    {{ __('signature.generate_pix_code') }}
                                </button>
                            </div>

                            <!-- Spinner de Carregamento -->
                            <div id="pixLoading" class="mb-4 hidden">
                                <div
                                    class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-green-500 hover:bg-green-400 transition ease-in-out duration-150 cursor-not-allowed">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    {{ __('signature.generating_pix_code') }}
                                </div>
                            </div>

                            <!-- Código PIX -->
                            <div id="pixContent" class="hidden">
                                <!-- Código PIX -->
                                <div class="mb-6">
                                    <label for="pixCode"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('signature.pix_code') }}
                                    </label>
                                    <div class="flex">
                                        <input type="text" id="pixCode" readonly
                                            class="flex-1 rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring-green-500"
                                            value="">
                                        <button onclick="copyPixCode()"
                                            class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 dark:border-gray-600 rounded-r-md bg-gray-50 dark:bg-gray-600 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-500 focus:z-10 focus:border-green-500 focus:ring-green-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Status do Pagamento -->
                                <div class="mb-6">
                                    <div id="paymentStatusContainer"
                                        class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
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
                                    <button id="checkStatusButton" onclick="checkPaymentStatus()"
                                        class="inline-flex items-center px-6 py-3 bg-blue-500 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                        {{ __('signature.check_payment_status') }}
                                    </button>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-global.content-card>
</x-app-layout>

<script>
    // Constantes do enum de status de pagamento
    const PAYMENT_STATUS = {
        PENDING: {{ $paymentStatusEnum::PENDING->value }},
        PAID: {{ $paymentStatusEnum::PAID->value }},
        REJECTED: {{ $paymentStatusEnum::REJECTED->value }},
        EXPIRED: {{ $paymentStatusEnum::EXPIRED->value }},
        OVERDUE: {{ $paymentStatusEnum::OVERDUE->value }}
    };

    let currentPaymentId = null;

    function generatePixCode() {
        // Mostrar loading
        document.getElementById('pixGenerateButton').classList.add('hidden');
        document.getElementById('pixLoading').classList.remove('hidden');
        document.getElementById('pixContent').classList.add('hidden');

        // Obter o token CSRF
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Obter o ID da assinatura da URL ou de um elemento da página
        const signatureId = {{ $signature->id }};

        // Fazer a requisição POST
        fetch(`/signature/${signatureId}/generate-pix`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                // Esconder loading
                document.getElementById('pixLoading').classList.add('hidden');

                if (data.success && data.data && data.data.id) {
                    // Salvar o ID do pagamento para uso posterior
                    currentPaymentId = data.data.id;

                    // Fazer segunda requisição para obter o código PIX
                    return fetch(`/signature/${signatureId}/get-pix-code`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            payment_id: data.data.id
                        })
                    });
                } else {
                    // Em caso de erro, mostrar o botão novamente
                    document.getElementById('pixGenerateButton').classList.remove('hidden');
                    throw new Error('Erro ao gerar pagamento');
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(pixData => {
                if (pixData.success && pixData.data && pixData.data.encodedImage) {
                    // Mostrar conteúdo do PIX
                    document.getElementById('pixContent').classList.remove('hidden');
                    document.getElementById('pixCode').value = pixData.data.encodedImage;
                } else {
                    // Em caso de erro, mostrar o botão novamente
                    document.getElementById('pixGenerateButton').classList.remove('hidden');
                }
            })
            .catch(error => {
                // Esconder loading e mostrar botão novamente em caso de erro
                document.getElementById('pixLoading').classList.add('hidden');
                document.getElementById('pixGenerateButton').classList.remove('hidden');
            });
    }

    function copyPixCode() {
        const pixCodeInput = document.getElementById('pixCode');
        pixCodeInput.select();
        pixCodeInput.setSelectionRange(0, 99999); // Para dispositivos móveis

        try {
            document.execCommand('copy');
            // Mostrar feedback visual
            showNotification('{{ __("signature.pix_code_copied") }}', 'success');
        } catch (err) {
            showNotification('Erro ao copiar código PIX', 'error');
        }
    }

    function checkPaymentStatus() {
        if (!currentPaymentId) {
            showNotification('{{ __("signature.payment_not_found") }}', 'error');
            return;
        }

        // Desabilitar botão e mostrar loading
        const checkButton = document.getElementById('checkStatusButton');
        const originalText = checkButton.innerHTML;
        checkButton.disabled = true;
        checkButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Verificando...
        `;

        // Obter o token CSRF
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const signatureId = {{ $signature->id }};

        // Fazer a requisição para verificar o status
        fetch(`/signature/${signatureId}/check-payment-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    payment_id: currentPaymentId
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                // Restaurar botão
                checkButton.disabled = false;
                checkButton.innerHTML = originalText;

                if (data.success) {
                    updatePaymentStatus(data.status, data.internal_status);

                    if (data.status === 'CONFIRMED' || data.status === 'RECEIVED' || data.internal_status === PAYMENT_STATUS.PAID) {
                        showNotification('{{ __("signature.payment_confirmed") }}', 'success');
                        // Redirecionar após 2 segundos
                        setTimeout(() => {
                            window.location.href = '{{ route("signature.index") }}';
                        }, 2000);
                    } else if (data.status === 'OVERDUE' || data.internal_status === PAYMENT_STATUS.OVERDUE) {
                        showNotification('{{ __("signature.payment_overdue_message") }}', 'warning');
                    } else if (data.status === 'REJECTED' || data.status === 'CANCELLED' || data.internal_status === PAYMENT_STATUS.REJECTED) {
                        showNotification('{{ __("signature.payment_rejected_message") }}', 'error');
                    } else {
                        showNotification('{{ __("signature.payment_still_pending") }}', 'info');
                    }
                } else {
                    showNotification(data.message || 'Erro ao verificar status', 'error');
                }
            })
            .catch(error => {
                // Restaurar botão
                checkButton.disabled = false;
                checkButton.innerHTML = originalText;

                showNotification('Erro ao verificar status do pagamento', 'error');
            });
    }

    function updatePaymentStatus(asaasStatus, internalStatus) {
        const statusContainer = document.getElementById('paymentStatusContainer');

        // Mapear status para cores e mensagens
        let statusConfig = {
            bgColor: 'bg-yellow-50 dark:bg-yellow-900/20',
            borderColor: 'border-yellow-200 dark:border-yellow-800',
            iconColor: 'text-yellow-400',
            textColor: 'text-yellow-800 dark:text-yellow-200',
            messageColor: 'text-yellow-700 dark:text-yellow-300',
            title: '{{ __("signature.payment_pending") }}',
            message: '{{ __("signature.payment_pending_message") }}',
            icon: `<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />`
        };

        if (asaasStatus === 'CONFIRMED' || asaasStatus === 'RECEIVED' || internalStatus === PAYMENT_STATUS.PAID) {
            statusConfig = {
                bgColor: 'bg-green-50 dark:bg-green-900/20',
                borderColor: 'border-green-200 dark:border-green-800',
                iconColor: 'text-green-400',
                textColor: 'text-green-800 dark:text-green-200',
                messageColor: 'text-green-700 dark:text-green-300',
                title: '{{ __("signature.payment_status_paid") }}',
                message: '{{ __("signature.payment_confirmed") }}',
                icon: `<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />`
            };
        } else if (asaasStatus === 'REJECTED' || asaasStatus === 'CANCELLED' || internalStatus === PAYMENT_STATUS.REJECTED) {
            statusConfig = {
                bgColor: 'bg-red-50 dark:bg-red-900/20',
                borderColor: 'border-red-200 dark:border-red-800',
                iconColor: 'text-red-400',
                textColor: 'text-red-800 dark:text-red-200',
                messageColor: 'text-red-700 dark:text-red-300',
                title: '{{ __("signature.payment_status_rejected") }}',
                message: '{{ __("signature.payment_rejected_message") }}',
                icon: `<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />`
            };
        } else if (asaasStatus === 'OVERDUE' || internalStatus === PAYMENT_STATUS.OVERDUE) {
            statusConfig = {
                bgColor: 'bg-orange-50 dark:bg-orange-900/20',
                borderColor: 'border-orange-200 dark:border-orange-800',
                iconColor: 'text-orange-400',
                textColor: 'text-orange-800 dark:text-orange-200',
                messageColor: 'text-orange-700 dark:text-orange-300',
                title: '{{ __("signature.payment_status_overdue") }}',
                message: '{{ __("signature.payment_overdue_message") }}',
                icon: `<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />`
            };
        }

        // Atualizar o container de status
        statusContainer.className = `${statusConfig.bgColor} ${statusConfig.borderColor} rounded-lg p-4`;
        statusContainer.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 ${statusConfig.iconColor}" viewBox="0 0 20 20" fill="currentColor">
                        ${statusConfig.icon}
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium ${statusConfig.textColor}">
                        ${statusConfig.title}
                    </h3>
                    <div class="mt-2 text-sm ${statusConfig.messageColor}">
                        <p>${statusConfig.message}</p>
                    </div>
                </div>
            </div>
        `;
    }

    function showNotification(message, type = 'info') {
        // Criar elemento de notificação
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

        // Definir cores baseadas no tipo
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            info: 'bg-blue-500 text-white',
            warning: 'bg-yellow-500 text-white'
        };

        notification.className += ` ${colors[type] || colors.info}`;
        notification.innerHTML = message;

        // Adicionar ao DOM
        document.body.appendChild(notification);

        // Animar entrada
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Remover após 3 segundos
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
</script>
