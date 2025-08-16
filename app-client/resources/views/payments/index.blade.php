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

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
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
</x-app-layout>
