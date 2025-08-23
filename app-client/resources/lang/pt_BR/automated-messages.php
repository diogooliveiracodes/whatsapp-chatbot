<?php

return [
    'automated_messages' => 'Mensagens Automatizadas',
    'automated_message' => 'Mensagem Automatizada',
    'new_automated_message' => 'Nova Mensagem Automatizada',
    'edit_automated_message' => 'Editar Mensagem Automatizada',
    'create' => 'Criar Mensagem',
    'update' => 'Atualizar Mensagem',
    'delete' => 'Excluir Mensagem',
    'back' => 'Voltar',
    'actions' => 'Ações',
    'no_messages' => 'Nenhuma mensagem automatizada encontrada',
    'no_messages_description' => 'Comece criando sua primeira mensagem automatizada para melhorar a comunicação com seus clientes.',

    'fields' => [
        'name' => 'Nome',
        'type' => 'Tipo',
        'content' => 'Conteúdo da Mensagem',

        'unit' => 'Unidade',
        'created_by' => 'Criado por',
        'created_at' => 'Criado em',
        'updated_at' => 'Atualizado em',
    ],

    'types' => [
        'schedule_confirmation' => 'Confirmação de Agendamento',
        'schedule_reminder' => 'Lembrete de Agendamento',
        'schedule_cancellation' => 'Cancelamento de Agendamento',
        'schedule_reschedule' => 'Reagendamento',
        'payment_confirmation' => 'Confirmação de Pagamento',
        'payment_reminder' => 'Lembrete de Pagamento',
        'welcome_message' => 'Mensagem de Boas-vindas',
        'custom_message' => 'Mensagem Personalizada',
    ],

    'descriptions' => [
        'schedule_confirmation' => 'Enviada quando um agendamento é confirmado',
        'schedule_reminder' => 'Enviada como lembrete antes do agendamento',
        'schedule_cancellation' => 'Enviada quando um agendamento é cancelado',
        'schedule_reschedule' => 'Enviada quando um agendamento é reagendado',
        'payment_confirmation' => 'Enviada quando um pagamento é confirmado',
        'payment_reminder' => 'Enviada como lembrete de pagamento pendente',
        'welcome_message' => 'Enviada para novos clientes',
        'custom_message' => 'Mensagem personalizada para uso específico',
    ],

    'messages' => [
        'created' => 'Mensagem automatizada criada com sucesso!',
        'updated' => 'Mensagem automatizada atualizada com sucesso!',
        'deleted' => 'Mensagem automatizada excluída com sucesso!',

        'validation_error' => 'Erro de validação',
        'create_error' => 'Erro ao criar mensagem automatizada: :message',
        'update_error' => 'Erro ao atualizar mensagem automatizada: :message',
        'delete_error' => 'Erro ao excluir mensagem automatizada: :message',
        'load_error' => 'Erro ao carregar mensagens automatizadas.',
        'name_required' => 'Nome é obrigatório',
        'name_max' => 'Nome não pode ter mais de 255 caracteres',
        'type_required' => 'Tipo é obrigatório',
        'type_invalid' => 'Tipo inválido',
        'content_required' => 'Conteúdo da mensagem é obrigatório',
        'content_max' => 'Conteúdo da mensagem não pode ter mais de 1000 caracteres',
        'unit_required' => 'Unidade é obrigatória',
        'unit_not_found' => 'Unidade selecionada não existe',
        'unit_not_belongs_to_company' => 'Unidade não pertence à sua empresa',
        'confirm_delete' => 'Tem certeza que deseja excluir esta mensagem automatizada?',
        'created_description' => 'A mensagem automatizada foi criada com sucesso e está disponível para uso.',
        'updated_description' => 'A mensagem automatizada foi atualizada com sucesso.',
    ],

    'placeholders' => [
        'name' => 'Digite o nome da mensagem',
        'content' => 'Digite o conteúdo da mensagem...',
        'search' => 'Buscar mensagens...',
    ],

    'variables' => [
        'title' => 'Variáveis Disponíveis',
        'description' => 'Use as seguintes variáveis no conteúdo da mensagem:',
        'customer_name' => '{customer_name} - Nome do cliente',
        'customer_phone' => '{customer_phone} - Telefone do cliente',
        'schedule_date' => '{schedule_date} - Data do agendamento',
        'schedule_time' => '{schedule_time} - Horário do agendamento',
        'service_name' => '{service_name} - Nome do serviço',
        'unit_name' => '{unit_name} - Nome da unidade',
        'company_name' => '{company_name} - Nome da empresa',
        'payment_amount' => '{payment_amount} - Valor do pagamento',
        'payment_method' => '{payment_method} - Método de pagamento',
    ],



    'unit_selection' => 'Seleção de Unidade',
    'show_unit_selector' => 'Mostrar seletor de unidade',
    'filter_by_unit' => 'Filtrar por unidade',
    'all_units' => 'Todas as unidades',
];
