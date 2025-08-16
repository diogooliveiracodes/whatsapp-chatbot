@props(['block'])

@php
    // Verificar se o bloqueio já passou usando o fuso horário da unidade
    $blockDateTime = null;

    // Obter o fuso horário da unidade do usuário logado
    $userTimezone = auth()->user()->unit->unitSettings->timezone ?? 'UTC';

    if ($block['block_type']->value === 'full_day') {
        // Para bloqueios de dia inteiro, usar o final do dia
        $blockDateTime = \Carbon\Carbon::parse($block['block_date'])->endOfDay();
    } else {
        // Para bloqueios de horário específico, usar a data + hora de término
        $blockDateTime = \Carbon\Carbon::parse($block['block_date'])->setTimeFromTimeString($block['end_time']);
    }

    // Converter para o fuso horário da unidade
    $currentTimeInUserTimezone = now()->setTimezone($userTimezone);

    // Um bloqueio só "passou" quando o horário de término já foi ultrapassado
    $isPastBlock = $currentTimeInUserTimezone->gt($blockDateTime);
@endphp

<div class="bg-red-50 dark:bg-red-900/20 rounded-lg shadow-sm border border-red-200 dark:border-red-800">
    <div class="p-2">
        <div class="mb-1 flex justify-between space-x-1">
            <span class="px-1.5 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                @if ($block['block_type']->value === 'full_day' || $block['block_type'] === 'full_day')
                    {{ __('schedule-blocks.types.full_day') }}
                @else
                    {{ __('schedule-blocks.types.time_slot') }}
                @endif
            </span>
            @if (!$isPastBlock)
                <div class="flex space-x-2">
                    <a href="{{ route('schedule-blocks.edit', $block['id']) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>
        <div class="flex items-center justify-between mb-1">
            <h3 class="text-sm font-semibold text-red-900 dark:text-red-200 truncate">
                @if ($block['block_type']->value === 'full_day' || $block['block_type'] === 'full_day')
                    {{ __('schedule-blocks.full_day_text') }}
                @else
                    {{ $block['start_time'] }} - {{ $block['end_time'] }}
                @endif
            </h3>
        </div>
        @if($block['reason'])
            <p class="text-xs text-red-700 dark:text-red-300 truncate">
                {{ $block['reason'] }}
            </p>
        @endif
        <p class="text-xs text-red-600 dark:text-red-400 truncate">
            {{ __('schedule-blocks.created_by') }}: {{ $block['user']['name'] }}
        </p>
    </div>
</div>
