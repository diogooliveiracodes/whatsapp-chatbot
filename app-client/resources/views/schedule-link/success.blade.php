<x-guest-layout>
    @section('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endsection

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Success Message -->
                    <div class="mb-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/20 mb-4">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-semibold text-white mb-2">{{ __('schedule_link.success_title') }}</h1>
                    </div>

                    <!-- Schedule Card -->
                    @if(isset($schedule))
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-white mb-4 text-center">
                                {{ __('schedules.created_schedule_details') }}
                            </h4>
                            <div class="border border-gray-700 rounded-lg overflow-hidden">
                                <div class="flex items-center justify-between p-4 bg-gray-700">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-16 flex flex-col items-left justify-center">
                                            <span class="text-white font-bold leading-tight">
                                                {{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}
                                            </span>
                                            <span class="text-gray-400 text-xs">
                                                {{ \Carbon\Carbon::parse($schedule['schedule_date'])->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900/30 text-blue-300 border border-blue-700">
                                            {{ __('schedules.booked_slots') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-4 bg-gray-800 border-t border-gray-700">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-400">Cliente</div>
                                            <div class="text-sm text-white">{{ $schedule['customer']['name'] ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-400">Serviço</div>
                                            <div class="text-sm text-white">{{ $schedule['unit_service_type']['name'] ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-400">Unidade</div>
                                            <div class="text-sm text-white">{{ $schedule['unit']['name'] ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-400">Status</div>
                                            <div class="text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($schedule['status'] === 'confirmed') bg-green-900/30 text-green-300 border border-green-700
                                                @elseif($schedule['status'] === 'pending') bg-yellow-900/30 text-yellow-300 border border-yellow-700
                                                @else bg-red-900/30 text-red-300 border border-red-700 @endif">
                                                    {{ __('schedules.statuses.' . $schedule['status']) }}
                                                </span>
                                            </div>
                                        </div>
                                        @if ($schedule['notes'])
                                            <div class="sm:col-span-2 lg:col-span-3">
                                                <div class="text-sm font-medium text-gray-400">Observações</div>
                                                <div class="text-sm text-white">{{ $schedule['notes'] }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Section -->
                    @if(isset($schedule) && $schedule['id'])
                        <div class="mb-6">
                            <div class="bg-gray-700/50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-white mb-4 text-center">
                                    {{ __('schedule_link.payment_section_title') }}
                                </h3>

                                <!-- Payment Amount -->
                                <div class="text-center mb-4">
                                    <div class="text-2xl font-bold text-white">
                                        {{ __('schedule_link.payment_amount', ['amount' => number_format($schedule['unit_service_type']['price'] ?? 0, 2, ',', '.')]) }}
                                    </div>
                                </div>

                                <!-- PIX Payment Section -->
                                <div id="pixPaymentSection" class="space-y-4">
                                    <!-- Document Number Field (only if customer doesn't have it) -->
                                    @if(empty($schedule['customer']['document_number']))
                                        <div id="documentNumberSection" class="space-y-2">
                                            <label for="document_number" class="block text-gray-300 text-sm font-medium">
                                                {{ __('schedule_link.document_number') }} <span class="text-red-400">*</span>
                                            </label>
                                            <input type="text" id="document_number" name="document_number"
                                                placeholder="000.000.000-00 ou 00.000.000/0000-00"
                                                class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700/50 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                            <div class="text-xs text-gray-400">
                                                {{ __('schedule_link.document_required_for_pix') }}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Generate PIX Button -->
                                    <div id="pixGenerateButton" class="text-center">
                                        <button onclick="generatePixCode()"
                                                class="inline-flex items-center justify-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            {{ __('schedule_link.generate_pix') }}
                                        </button>
                                    </div>

                                    <!-- Loading State -->
                                    <div id="pixLoading" class="hidden text-center">
                                        <div class="inline-flex items-center px-4 py-2 bg-gray-600 rounded-md">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            {{ __('schedule_link.generating_pix') }}
                                        </div>
                                    </div>

                                    <!-- PIX Code Display -->
                                    <div id="pixContent" class="hidden">
                                        <div class="bg-gray-800 rounded-lg p-4 border border-gray-600">
                                            <h4 class="text-sm font-medium text-gray-300 mb-2">{{ __('schedule_link.pix_code_label') }}</h4>
                                            <div class="space-y-3">
                                                <input type="text" id="pixCode" readonly
                                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white text-sm font-mono">
                                                <button onclick="copyPixCode()"
                                                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ __('schedule_link.copy_pix') }}
                                                </button>
                                            </div>
                                        </div>

                                        <div class="text-center text-sm text-gray-400">
                                            {{ __('schedule_link.pix_instructions') }}
                                        </div>

                                        <!-- Status do Pagamento -->
                                        <div class="mt-6">
                                            <div id="paymentStatusContainer"
                                                class="bg-yellow-900/20 border border-yellow-700 rounded-lg p-4">
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
                                                        <h3 class="text-sm font-medium text-yellow-300">
                                                            {{ __('schedule_link.payment_pending') }}
                                                        </h3>
                                                        <div class="mt-2 text-sm text-yellow-200">
                                                            <p>{{ __('schedule_link.payment_pending_message') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Botão de Verificar Status -->
                                        <div class="mt-4 text-center">
                                            <button id="checkStatusButton" onclick="checkPaymentStatus()"
                                                class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                    </path>
                                                </svg>
                                                {{ __('schedule_link.check_payment_status') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="text-center space-y-4">
                        <div>
                            <a href="{{ route('schedule-link.show', ['company' => $company, 'unit' => $unit->id]) }}"
                               class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('schedule_link.book_another') }}
                            </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

<script>
    // Constantes do enum de status de pagamento
    const PAYMENT_STATUS = {
        PENDING: 1,
        PAID: 2,
        REJECTED: 3,
        EXPIRED: 4,
        OVERDUE: 5
    };

    let currentPaymentId = null;

    function generatePixCode() {
        // Check if document_number is required and validate it
        const documentNumberInput = document.getElementById('document_number');
        if (documentNumberInput && !documentNumberInput.value.trim()) {
            showNotification('{{ __('schedule_link.document_number_required') }}', 'error');
            documentNumberInput.focus();
            return;
        }

        // Show loading state
        document.getElementById('pixGenerateButton').classList.add('hidden');
        document.getElementById('pixLoading').classList.remove('hidden');
        document.getElementById('pixContent').classList.add('hidden');

        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Get schedule ID from session data
        const scheduleId = {{ $schedule['id'] ?? 'null' }};
        const companyId = {{ $company }};

        if (!scheduleId) {
            showNotification('{{ __('schedule_link.schedule_not_found') }}', 'error');
            resetPixSection();
            return;
        }

        // Prepare request data
        const requestData = {};
        if (documentNumberInput && documentNumberInput.value.trim()) {
            requestData.document_number = documentNumberInput.value.trim();
        }

        // Make request to generate PIX
        fetch(`/${companyId}/schedule-link/schedule/${scheduleId}/generate-pix`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data && data.data.id) {
                currentPaymentId = data.data.id;

                // If it's an existing payment, try to get the PIX code directly
                if (data.data.existing_payment) {
                    loadExistingPixCode();
                } else {
                    // For new payments, get the PIX code
                    getPixCode();
                }
            } else {
                showNotification('{{ __('schedule_link.pix_generation_error') }}', 'error');
                resetPixSection();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('{{ __('schedule_link.pix_generation_error') }}', 'error');
            resetPixSection();
        });
    }

    function getPixCode() {
        if (!currentPaymentId) {
            showNotification('{{ __('schedule_link.payment_id_not_found') }}', 'error');
            resetPixSection();
            return;
        }

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const scheduleId = {{ $schedule['id'] ?? 'null' }};
        const companyId = {{ $company }};

        fetch(`/${companyId}/schedule-link/schedule/${scheduleId}/get-pix-code`, {
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
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                const pixCode = extractPixCode(data.data);
                if (pixCode) {
                    document.getElementById('pixCode').value = pixCode;
                    document.getElementById('pixLoading').classList.add('hidden');
                    document.getElementById('pixContent').classList.remove('hidden');

                    // Hide document number section after successful PIX generation
                    const documentSection = document.getElementById('documentNumberSection');
                    if (documentSection) {
                        documentSection.style.display = 'none';
                    }

                    showNotification('{{ __('schedule_link.pix_generated_success') }}', 'success');
                } else {
                    showNotification('{{ __('schedule_link.pix_code_not_found') }}', 'error');
                    resetPixSection();
                }
            } else {
                showNotification('{{ __('schedule_link.pix_code_error') }}', 'error');
                resetPixSection();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('{{ __('schedule_link.pix_code_error') }}', 'error');
            resetPixSection();
        });
    }

    function loadExistingPixCode() {
        // For existing payments, try to get PIX code directly
        getPixCode();
    }

    function extractPixCode(response) {
        // Check different possible fields in Asaas response
        const possibleFields = ['payload', 'encodedImage', 'pixCode', 'qrCode', 'copyPaste', 'pixCopyPaste'];

        for (const field of possibleFields) {
            if (response[field] && response[field].trim()) {
                return response[field];
            }
        }

        return null;
    }

    function copyPixCode() {
        const pixCodeInput = document.getElementById('pixCode');
        pixCodeInput.select();
        pixCodeInput.setSelectionRange(0, 99999); // For mobile devices

        try {
            document.execCommand('copy');
            showNotification('{{ __('schedule_link.pix_copied_success') }}', 'success');
        } catch (err) {
            // Fallback for modern browsers
            navigator.clipboard.writeText(pixCodeInput.value).then(() => {
                showNotification('{{ __('schedule_link.pix_copied_success') }}', 'success');
            }).catch(() => {
                showNotification('{{ __('schedule_link.pix_copy_error') }}', 'error');
            });
        }
    }

    function resetPixSection() {
        document.getElementById('pixGenerateButton').classList.remove('hidden');
        document.getElementById('pixLoading').classList.add('hidden');
        document.getElementById('pixContent').classList.add('hidden');
    }

    function checkPaymentStatus() {
        if (!currentPaymentId) {
            showNotification('{{ __('schedule_link.payment_not_found') }}', 'error');
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
        const scheduleId = {{ $schedule['id'] ?? 'null' }};
        const companyId = {{ $company }};

        if (!scheduleId) {
            showNotification('{{ __('schedule_link.schedule_not_found') }}', 'error');
            checkButton.disabled = false;
            checkButton.innerHTML = originalText;
            return;
        }

        // Fazer a requisição para verificar o status
        fetch(`/${companyId}/schedule-link/schedule/${scheduleId}/check-payment-status`, {
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

                if (data.success && data.data) {
                    const paymentData = data.data;
                    updatePaymentStatus(paymentData.status, paymentData.internal_status);

                    if (paymentData.status === 'CONFIRMED' || paymentData.status === 'RECEIVED' || paymentData.internal_status === PAYMENT_STATUS.PAID) {
                        showNotification('{{ __('schedule_link.payment_confirmed') }}', 'success');


                    } else if (paymentData.status === 'OVERDUE' || paymentData.internal_status === PAYMENT_STATUS.OVERDUE) {
                        showNotification('{{ __('schedule_link.payment_overdue_message') }}', 'warning');
                        // Redirecionar para novo agendamento após 3 segundos quando pagamento venceu
                        setTimeout(() => {
                            window.location.href = '{{ route("schedule-link.show", ["company" => $company, "unit" => $unit->id]) }}';
                        }, 8000);
                    } else if (paymentData.status === 'REJECTED' || paymentData.status === 'CANCELLED' || paymentData.internal_status === PAYMENT_STATUS.REJECTED) {
                        showNotification('{{ __('schedule_link.payment_rejected_message') }}', 'error');
                    } else {
                        showNotification('{{ __('schedule_link.payment_still_pending') }}', 'info');
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
            bgColor: 'bg-yellow-900/20',
            borderColor: 'border-yellow-700',
            iconColor: 'text-yellow-400',
            textColor: 'text-yellow-300',
            messageColor: 'text-yellow-200',
            title: '{{ __('schedule_link.payment_pending') }}',
            message: '{{ __('schedule_link.payment_pending_message') }}',
            icon: `<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />`
        };

        if (asaasStatus === 'CONFIRMED' || asaasStatus === 'RECEIVED' || internalStatus === PAYMENT_STATUS.PAID) {
            statusConfig = {
                bgColor: 'bg-green-900/20',
                borderColor: 'border-green-700',
                iconColor: 'text-green-400',
                textColor: 'text-green-300',
                messageColor: 'text-green-200',
                title: '{{ __('schedule_link.payment_status_paid') }}',
                message: '{{ __('schedule_link.payment_confirmed') }}',
                icon: `<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />`
            };
        } else if (asaasStatus === 'REJECTED' || asaasStatus === 'CANCELLED' || internalStatus === PAYMENT_STATUS.REJECTED) {
            statusConfig = {
                bgColor: 'bg-red-900/20',
                borderColor: 'border-red-700',
                iconColor: 'text-red-400',
                textColor: 'text-red-300',
                messageColor: 'text-red-200',
                title: '{{ __('schedule_link.payment_status_rejected') }}',
                message: '{{ __('schedule_link.payment_rejected_message') }}',
                icon: `<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />`
            };
        } else if (asaasStatus === 'OVERDUE' || internalStatus === PAYMENT_STATUS.OVERDUE) {
            statusConfig = {
                bgColor: 'bg-orange-900/20',
                borderColor: 'border-orange-700',
                iconColor: 'text-orange-400',
                textColor: 'text-orange-300',
                messageColor: 'text-orange-200',
                title: '{{ __('schedule_link.payment_status_overdue') }}',
                message: '{{ __('schedule_link.payment_overdue_message') }}',
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

    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium ${
            type === 'success' ? 'bg-green-600' :
            type === 'error' ? 'bg-red-600' :
            type === 'warning' ? 'bg-yellow-600' : 'bg-blue-600'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>


