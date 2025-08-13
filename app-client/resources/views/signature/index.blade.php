<x-app-layout>
    <x-global.header>
        {{ __('signature.title') }}
    </x-global.header>

    <x-global.content-card title="{{ __('signature.details') }}">
        <div class="space-y-6">
            <!-- Informações da Assinatura -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('signature.current_plan') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Plano Atual -->
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

                    <!-- Status da Assinatura -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('signature.status') }}
                        </h4>
                        <span
                            class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if ($signature->status === \App\Enum\SignatureStatusEnum::PAID->value) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($signature->status === \App\Enum\SignatureStatusEnum::PENDING->value)
                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($signature->status === \App\Enum\SignatureStatusEnum::EXPIRED->value)
                                bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @elseif($signature->status === \App\Enum\SignatureStatusEnum::EXPIRING_SOON->value)
                                bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @else
                                bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                            {{ \App\Enum\SignatureStatusEnum::from($signature->status)->name() }}
                        </span>
                    </div>

                    <!-- Data de Expiração -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('signature.expires_at') }}
                        </h4>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $signature->expires_at ? $signature->expires_at->format('d/m/Y H:i') : __('signature.no_expiration') }}
                        </p>
                        @if ($signature->expires_at)
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                @php
                                    $daysLeft = intval(now()->diffInDays($signature->expires_at, false));
                                @endphp
                                @if ($daysLeft > 0)
                                    {{ __('signature.days_left', ['days' => $daysLeft]) }}
                                @elseif($daysLeft < 0)
                                    {{ __('signature.expired_days_ago', ['days' => abs($daysLeft)]) }}
                                @else
                                    {{ __('signature.expires_today') }}
                                @endif
                            </p>
                        @endif
                    </div>

                    <!-- Valor do Plano -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                            {{ __('signature.plan_price') }}
                        </h4>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">
                            R$ {{ number_format($signature->plan->price, 2, ',', '.') }}
                        </p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            {{ __('signature.per_month') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- WhatsApp do Suporte -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('signature.support_contact') }}
                </h3>

                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            {{ __('signature.support_message') }}
                        </p>
                        <a href="https://wa.me/5511999999999?text={{ urlencode(__('signature.whatsapp_message') . ' Empresa: ' . auth()->user()->company->name) }}"
                            target="_blank"
                            class="inline-flex items-center mt-2 px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                            </svg>
                            {{ __('signature.contact_support') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Histórico de Pagamentos -->
            @if ($signature->payments->count() > 0)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('signature.payment_history') }}
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('signature.payment_date') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('signature.payment_amount') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('signature.payment_status') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($signature->payments as $payment)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $payment->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            R$ {{ number_format($payment->amount, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($payment->status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($payment->status === 'pending')
                                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @else
                                                    bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                @switch($payment->status)
                                                    @case('paid')
                                                        {{ __('signature.payment_status_paid') }}
                                                        @break
                                                    @case('pending')
                                                        {{ __('signature.payment_status_pending') }}
                                                        @break
                                                    @case('rejected')
                                                        {{ __('signature.payment_status_rejected') }}
                                                        @break
                                                    @case('cancelled')
                                                        {{ __('signature.payment_status_cancelled') }}
                                                        @break
                                                    @default
                                                        {{ ucfirst($payment->status) }}
                                                @endswitch
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </x-global.content-card>
</x-app-layout>
