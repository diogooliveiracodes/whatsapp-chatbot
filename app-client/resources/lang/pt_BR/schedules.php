<?php

return [
    'messages' => [
        'outside_working_days' => 'O agendamento não pode ser feito fora dos dias úteis.',
        'outside_working_hours' => 'O agendamento deve estar dentro do horário de funcionamento.',
        'time_conflict' => 'Já existe um agendamento neste horário.',
        'created' => 'Agendamento criado com sucesso!',
        'updated' => 'Agendamento atualizado com sucesso!',
        'deleted' => 'Agendamento excluído com sucesso!',
        'validation_error' => 'Erro de validação',
        'create_error' => 'Erro ao criar agendamento: :message',
        'update_error' => 'Erro ao atualizar agendamento: :message',
        'customer_required' => 'O cliente é obrigatório',
        'customer_not_found' => 'O cliente selecionado não existe',
        'date_required' => 'A data do agendamento é obrigatória',
        'invalid_date' => 'Formato de data inválido',
        'start_time_required' => 'A hora de início é obrigatória',
        'end_time_required' => 'A hora de término é obrigatória',
        'invalid_time_format' => 'Formato de hora inválido. Use o formato HH:mm',
        'end_time_after_start' => 'A hora de término deve ser posterior à hora de início',
        'service_type_required' => 'O tipo de serviço é obrigatório',
    ],
    'new_schedule' => 'Novo Agendamento',
];
