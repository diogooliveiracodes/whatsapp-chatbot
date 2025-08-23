<x-app-layout>
    <x-global.header>
        {{ __('payments.payment_history') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                                        <x-buttons.back-button
                        :route="route('signature.index')"
                        :text="__('payments.back_to_signature')"
                    />

                    <!-- Tabela para desktop -->
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('payments.payment_date') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('payments.payment_amount') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('payments.payment_status') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('payments.payment_method') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('payments.plan') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('payments.pix_code') }}
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($payments as $payment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $payment->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            R$ {{ number_format($payment->amount, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($payment->status->value === $paymentStatusEnum::PAID->value) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($payment->status->value === $paymentStatusEnum::PENDING->value)
                                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($payment->status->value === $paymentStatusEnum::OVERDUE->value)
                                                    bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                @else
                                                    bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                @switch($payment->status->value)
                                                    @case($paymentStatusEnum::PAID->value)
                                                        {{ __('payments.payment_status_paid') }}
                                                    @break

                                                    @case($paymentStatusEnum::PENDING->value)
                                                        {{ __('payments.payment_status_pending') }}
                                                    @break

                                                    @case($paymentStatusEnum::REJECTED->value)
                                                        {{ __('payments.payment_status_rejected') }}
                                                    @break

                                                    @case($paymentStatusEnum::EXPIRED->value)
                                                        {{ __('payments.payment_status_expired') }}
                                                    @break

                                                    @case($paymentStatusEnum::OVERDUE->value)
                                                        {{ __('payments.payment_status_overdue') }}
                                                    @break

                                                    @default
                                                        {{ ucfirst($payment->status->name()) }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $payment->payment_method->name() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $payment->plan->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @if ($payment->status->value === $paymentStatusEnum::PENDING->value && $payment->payment_method->value === $paymentMethodEnum::PIX->value && $payment->pix_copy_paste)
                                                <button onclick="togglePixCode('pix-code-{{ $payment->id }}')"
                                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                                    {{ __('payments.show_pix_code') }}
                                                </button>
                                                <div id="pix-code-{{ $payment->id }}" class="hidden mt-2">
                                                    <div class="flex items-center space-x-2">
                                                        <input type="text"
                                                               id="pix-input-{{ $payment->id }}"
                                                               value="{{ $payment->pix_copy_paste }}"
                                                               readonly
                                                               class="flex-1 text-xs bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded px-2 py-1">
                                                        <button onclick="copyPixCode('pix-input-{{ $payment->id }}')"
                                                                class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('payments.no_payments_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Cards para mobile -->
                    <div class="md:hidden space-y-4">
                        @forelse ($payments as $payment)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $payment->created_at->format('d/m/Y H:i') }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ __('payments.plan') }}: {{ $payment->plan->name ?? 'N/A' }}
                                        </p>

                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($payment->status->value === $paymentStatusEnum::PAID->value) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($payment->status->value === $paymentStatusEnum::PENDING->value)
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($payment->status->value === $paymentStatusEnum::OVERDUE->value)
                                            bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                        @else
                                            bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                        @switch($payment->status->value)
                                            @case($paymentStatusEnum::PAID->value)
                                                {{ __('payments.payment_status_paid') }}
                                            @break

                                            @case($paymentStatusEnum::PENDING->value)
                                                {{ __('payments.payment_status_pending') }}
                                            @break

                                            @case($paymentStatusEnum::REJECTED->value)
                                                {{ __('payments.payment_status_rejected') }}
                                            @break

                                            @case($paymentStatusEnum::EXPIRED->value)
                                                {{ __('payments.payment_status_expired') }}
                                            @break

                                            @case($paymentStatusEnum::OVERDUE->value)
                                                {{ __('payments.payment_status_overdue') }}
                                            @break

                                            @default
                                                {{ ucfirst($payment->status->name()) }}
                                        @endswitch
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">{{ __('payments.payment_amount') }}:</span>
                                        R$ {{ number_format($payment->amount, 2, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">{{ __('payments.payment_method') }}:</span>
                                        {{ $payment->payment_method->name() }}
                                    </p>
                                </div>

                                <!-- Código PIX para pagamentos pendentes -->
                                @if ($payment->status->value === $paymentStatusEnum::PENDING->value && $payment->payment_method->value === $paymentMethodEnum::PIX->value && $payment->pix_copy_paste)
                                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                        <button onclick="togglePixCode('mobile-pix-code-{{ $payment->id }}')"
                                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium text-sm">
                                            {{ __('payments.show_pix_code') }}
                                        </button>
                                        <div id="mobile-pix-code-{{ $payment->id }}" class="hidden mt-2">
                                            <div class="bg-gray-100 dark:bg-gray-600 rounded-lg p-3">
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    {{ __('payments.pix_code') }}:
                                                </label>
                                                <div class="flex items-center space-x-2">
                                                    <input type="text"
                                                           id="mobile-pix-input-{{ $payment->id }}"
                                                           value="{{ $payment->pix_copy_paste }}"
                                                           readonly
                                                           class="flex-1 text-xs bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded px-2 py-1">
                                                    <button onclick="copyPixCode('mobile-pix-input-{{ $payment->id }}')"
                                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                    <p class="text-lg font-medium">{{ __('payments.no_payments_found') }}</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Paginador -->
                    @if ($payments->count() > 0)
                        <div class="mt-6">
                            {{ $payments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePixCode(elementId) {
            const element = document.getElementById(elementId);
            const button = element.previousElementSibling;

            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden');
                button.textContent = '{{ __("payments.hide_pix_code") }}';
            } else {
                element.classList.add('hidden');
                button.textContent = '{{ __("payments.show_pix_code") }}';
            }
        }

        function copyPixCode(inputId) {
            const input = document.getElementById(inputId);
            input.select();
            input.setSelectionRange(0, 99999); // Para dispositivos móveis

            try {
                document.execCommand('copy');

                // Feedback visual temporário
                const button = input.nextElementSibling;
                const originalHTML = button.innerHTML;
                button.innerHTML = `
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;

                setTimeout(() => {
                    button.innerHTML = originalHTML;
                }, 2000);

                // Mostrar notificação
                showNotification('{{ __("payments.pix_code_copied") }}', 'success');
            } catch (err) {
                console.error('Erro ao copiar código PIX:', err);
                showNotification('Erro ao copiar código PIX', 'error');
            }
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
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    </script>
</x-app-layout>
