<?php

return [
    'title' => 'Painel',
    'welcome_message' => 'Você está logado!',
    'owner' => [
        'insights' => [
            'title' => 'Visão geral da empresa',
            'description' => 'Principais números em tempo real',
        ],
        'kpis' => [
            'schedules_today' => 'Agendamentos Hoje',
            'schedules_month' => 'Agendamentos no Mês',
            'schedules_year' => 'Agendamentos no Ano',
            'cancellations_today' => 'Cancelamentos Hoje',
            'cancellations_month' => 'Cancelamentos no Mês',
            'cancellations_year' => 'Cancelamentos no Ano',
            'payments_received' => 'Recebimentos',
            'payments_receivable' => 'A Receber',
            'schedules_pending' => 'Agendamentos Pendentes',
        ],
        'charts' => [
            'schedules_by_month' => 'Agendamentos por Mês (Últimos 12 meses)',
            'schedules_by_weekday_30d' => 'Agendamentos por Dia da Semana (Últimos 30 dias)',
            'payments_by_month' => 'Pagamentos por Mês',
            'cancellations_by_month' => 'Cancelamentos por Mês',
        ],
    ],
    'schedule_link' => [
        'title' => 'Link de Agendamento',
        'label' => 'Link público para agendamento',
        'copy_button' => 'Copiar',
        'copied_message' => 'Copiado!',
        'error_message' => 'Erro',
        'description' => 'Compartilhe este link com seus clientes para que eles possam agendar horários diretamente.',
    ],
];
