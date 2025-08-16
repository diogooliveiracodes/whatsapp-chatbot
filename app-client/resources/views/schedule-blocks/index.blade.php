<x-app-layout>
    <x-global.header>
        {{ __('schedule-blocks.schedule_blocks') }}
    </x-global.header>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <x-global.session-alerts />

                    <div class="flex gap-4 mb-4 justify-between">
                        <x-global.create-button :route="route('schedule-blocks.create')" text="{{ __('schedule-blocks.create') }}" />
                    </div>

                    <!-- Info sobre o filtro de datas -->
                    <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            <i class="fas fa-info-circle mr-1"></i>
                            {{ __('schedule-blocks.showing_today_and_future_blocks') }}
                        </p>
                    </div>

                    <!-- Tabela para desktop -->
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('schedule-blocks.date') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('schedule-blocks.type') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('schedule-blocks.time') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('schedule-blocks.reason') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('schedule-blocks.company') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('schedule-blocks.created_by') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        {{ __('schedule-blocks.actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($blocks as $block)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $block->block_date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $block->block_type->value === 'full_day' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                {{ $block->getBlockTypeLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @if ($block['block_type']->value === 'time_slot' || $block['block_type'] === 'time_slot')
                                                @php
                                                    $userTimezone = auth()->user()->unit->unitSettings->timezone ?? 'UTC';
                                                    $blockDateString = $block->block_date instanceof \Carbon\Carbon ? $block->block_date->format('Y-m-d') : (string) $block->block_date;
                                                    $startDisplay = $block->start_time ? \Carbon\Carbon::parse($blockDateString . ' ' . $block->start_time, 'UTC')->setTimezone($userTimezone)->format('H:i') : null;
                                                    $endDisplay = $block->end_time ? \Carbon\Carbon::parse($blockDateString . ' ' . $block->end_time, 'UTC')->setTimezone($userTimezone)->format('H:i') : null;
                                                @endphp
                                                {{ $startDisplay }} - {{ $endDisplay }}
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400">{{ __('schedule-blocks.full_day_text') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $block['reason'] ?: __('schedule-blocks.no_reason') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $block['company']['name'] ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $block['user']['name'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @php
                                                // Obter o fuso horário da unidade do usuário logado
                                                $userTimezone = auth()->user()->unit->unitSettings->timezone ?? 'UTC';

                                                // Calcular término do bloqueio no fuso do usuário
                                                if (($block['block_type']->value ?? $block['block_type']) === 'full_day') {
                                                    $blockDateTime = \Carbon\Carbon::parse($block['block_date'], $userTimezone)->endOfDay();
                                                } else {
                                                    $blockDateString = $block->block_date instanceof \Carbon\Carbon ? $block->block_date->format('Y-m-d') : (string) $block->block_date;
                                                    $endTime = $block->end_time ?? ($block['end_time'] ?? '23:59');
                                                    $blockDateTime = \Carbon\Carbon::parse($blockDateString . ' ' . $endTime, 'UTC')->setTimezone($userTimezone);
                                                }

                                                // Agora no mesmo fuso
                                                $currentTimeInUserTimezone = now()->setTimezone($userTimezone);

                                                // Um bloqueio só "passou" quando o horário de término já foi ultrapassado
                                                $isPastBlock = $currentTimeInUserTimezone->gt($blockDateTime);
                                            @endphp
                                            @if (!$isPastBlock)
                                                <div class="flex items-center space-x-2">
                                                    <x-actions.edit :route="route('schedule-blocks.edit', $block['id'])" />
                                                    <x-actions.delete :route="route('schedule-blocks.destroy', $block['id'])" :confirmMessage="__('schedule-blocks.messages.confirm_delete')" />
                                                </div>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 text-xs">{{ __('schedule-blocks.past_block') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('schedule-blocks.no_blocks_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Cards para mobile -->
                    <div class="md:hidden space-y-4">
                        @forelse ($blocks as $block)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ \Carbon\Carbon::parse($block['block_date'])->format('d/m/Y') }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ __('schedule-blocks.company') }}: {{ $block['company']['name'] ?? 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ __('schedule-blocks.created_by') }}: {{ $block['user']['name'] }}
                                        </p>
                                    </div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ ($block['block_type']->value === 'full_day' || $block['block_type'] === 'full_day') ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                        {{ ($block['block_type']->value === 'full_day' || $block['block_type'] === 'full_day') ? __('schedule-blocks.types.full_day') : __('schedule-blocks.types.time_slot') }}
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">{{ __('schedule-blocks.time') }}:</span>
                                        @if ($block['block_type']->value === 'time_slot' || $block['block_type'] === 'time_slot')
                                            {{ $block['start_time'] }} - {{ $block['end_time'] }}
                                        @else
                                            {{ __('schedule-blocks.full_day_text') }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">{{ __('schedule-blocks.reason') }}:</span>
                                        {{ $block['reason'] ?: __('schedule-blocks.no_reason') }}
                                    </p>
                                </div>

                                                                @php
                                    // Verificar se o bloqueio já passou usando o fuso horário da unidade
                                    $userTimezone = auth()->user()->unit->unitSettings->timezone ?? 'UTC';
                                    $blockDateTime = null;
                                    if ($block['block_type']->value === 'full_day' || $block['block_type'] === 'full_day') {
                                        // Para bloqueios de dia inteiro, usar o final do dia (timezone do usuário)
                                        $blockDateTime = \Carbon\Carbon::parse($block['block_date'], $userTimezone)->endOfDay();
                                    } else {
                                        // Para bloqueios de horário específico, combinar data (UTC) + hora (UTC) e converter para timezone do usuário
                                        $blockDateString = $block->block_date instanceof \Carbon\Carbon ? $block->block_date->format('Y-m-d') : (string) $block->block_date;
                                        $endTime = $block->end_time ?? ($block['end_time'] ?? '23:59');
                                        $blockDateTime = \Carbon\Carbon::parse($blockDateString . ' ' . $endTime, 'UTC')->setTimezone($userTimezone);
                                    }

                                    $currentTimeInUserTimezone = now()->setTimezone($userTimezone);

                                    // Um bloqueio só "passou" quando o horário de término já foi ultrapassado
                                    $isPastBlock = $currentTimeInUserTimezone->gt($blockDateTime);
                                @endphp
                                @if (!$isPastBlock)
                                    <div class="flex justify-end space-x-2">
                                        <x-actions.edit-mobile :route="route('schedule-blocks.edit', $block['id'])" />
                                        <x-actions.delete-mobile :route="route('schedule-blocks.destroy', $block['id'])" :confirmMessage="__('schedule-blocks.messages.confirm_delete')" />
                                    </div>
                                @else
                                    <div class="flex justify-end">
                                        <span class="text-gray-400 dark:text-gray-500 text-xs">{{ __('schedule-blocks.past_block') }}</span>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-lg font-medium">{{ __('schedule-blocks.no_blocks_found') }}</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
