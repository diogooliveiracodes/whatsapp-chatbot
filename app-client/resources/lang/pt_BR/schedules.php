<?php

return [
    'messages' => [
        'closed' => 'Fechado',
        'outside_working_days' => 'O agendamento não pode ser realizado fora dos dias de funcionamento.',
        'outside_working_hours' => 'O agendamento deve estar dentro do horário de funcionamento.',
        'time_conflict' => 'Já existe um agendamento neste horário.',
        'created' => 'Agendamento criado com sucesso!',
        'updated' => 'Agendamento atualizado com sucesso!',
        'deleted' => 'Agendamento excluído com sucesso!',
        'cancelled' => 'Agendamento cancelado com sucesso!',
        'validation_error' => 'Erro de validação',
        'create_error' => 'Erro ao criar agendamento: :message',
        'update_error' => 'Erro ao atualizar agendamento: :message',
        'delete_error' => 'Erro ao excluir agendamento: :message',
        'cancel_error' => 'Erro ao cancelar agendamento: :message',
        'load_error' => 'Erro ao carregar agendamentos.',
        'customer_required' => 'Cliente é obrigatório',
        'customer_not_found' => 'Cliente selecionado não existe',
        'date_required' => 'Data do agendamento é obrigatória',
        'invalid_date' => 'Formato de data inválido',
        'start_time_required' => 'Horário de início é obrigatório',
        'end_time_required' => 'Horário de término é obrigatório',
        'invalid_time_format' => 'Formato de horário inválido. Use o formato HH:mm',
        'end_time_after_start' => 'Horário de término deve ser posterior ao horário de início',
        'service_type_required' => 'Tipo de serviço é obrigatório',
        'confirm_delete' => 'Tem certeza que deseja excluir este agendamento?',
    ],
    'today' => 'Hoje',
    'previous_week' => 'Semana Anterior',
    'next_week' => 'Próxima Semana',
    'new_schedule' => 'Novo Agendamento',
    'edit_schedule' => 'Editar Agendamento',
    'date' => 'Data',
    'start_time' => 'Horário de Início',
    'end_time' => 'Horário de Término',
    'service_type' => 'Tipo de Serviço',
    'status' => 'Status',
    'statuses' => [
        'pending' => 'Pendente',
        'confirmed' => 'Confirmado',
        'cancelled' => 'Cancelado',
    ],
    'notes' => 'Observações',
    'back' => 'Voltar',
    'create' => 'Criar Agendamento',
    'update' => 'Atualizar Agendamento',
];
