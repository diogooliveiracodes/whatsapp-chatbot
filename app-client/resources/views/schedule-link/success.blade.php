<x-guest-layout>
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
                        <p class="text-gray-300 mb-6">{{ __('schedule_link.success_message') }}</p>
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
                                            <div class="flex items-center space-x-2">
                                                <input type="text" id="pixCode" readonly
                                                       class="flex-1 px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white text-sm font-mono">
                                                <button onclick="copyPixCode()"
                                                        class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ __('schedule_link.copy_pix') }}
                                                </button>
                                            </div>
                                        </div>

                                        <div class="text-center text-sm text-gray-400">
                                            {{ __('schedule_link.pix_instructions') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Button -->
                    <div class="text-center">
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
    let currentPaymentId = null;

    function generatePixCode() {
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

        // Make request to generate PIX
        fetch(`/${companyId}/schedule-link/schedule/${scheduleId}/generate-pix`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
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

    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>


