<?php

return [
    'title' => 'Configurações da Unidade',
    'edit' => 'Editar Configurações',
    'back' => 'Voltar',
    'save_changes' => 'Salvar Alterações',
    'yes' => 'Sim',
    'no' => 'Não',
    'active' => 'Ativo',
    'minutes' => 'minutos',
    'appointment_duration_minutes' => 'Duração do agendamento em minutos',
    'start_time' => 'Horário de Início',
    'end_time' => 'Horário de Término',
    'no_working_hours_configured' => 'Nenhum horário de funcionamento configurado',

    // Basic Information
    'basic_info_section' => 'Informações Básicas',
    'name' => 'Nome',
    'phone' => 'Telefone',

    // Address Information
    'address_section' => 'Endereço',
    'street' => 'Rua',
    'number' => 'Número',
    'complement' => 'Complemento',
    'neighborhood' => 'Bairro',
    'city' => 'Cidade',
    'state' => 'Estado',
    'zipcode' => 'CEP',

    // WhatsApp Configuration
    'whatsapp_section' => 'Configurações do WhatsApp',
    'whatsapp_webhook_url' => 'URL do Webhook do WhatsApp',
    'whatsapp_number' => 'Número do WhatsApp',

    // Working Hours
    'working_hours_section' => 'Horário de Funcionamento',
    'sunday_start' => 'Horário de Início (Domingo)',
    'sunday_end' => 'Horário de Término (Domingo)',
    'monday_start' => 'Horário de Início (Segunda)',
    'monday_end' => 'Horário de Término (Segunda)',
    'tuesday_start' => 'Horário de Início (Terça)',
    'tuesday_end' => 'Horário de Término (Terça)',
    'wednesday_start' => 'Horário de Início (Quarta)',
    'wednesday_end' => 'Horário de Término (Quarta)',
    'thursday_start' => 'Horário de Início (Quinta)',
    'thursday_end' => 'Horário de Término (Quinta)',
    'friday_start' => 'Horário de Início (Sexta)',
    'friday_end' => 'Horário de Término (Sexta)',
    'saturday_start' => 'Horário de Início (Sábado)',
    'saturday_end' => 'Horário de Término (Sábado)',
    'working_days' => 'Dias de Funcionamento',
    'sunday' => 'Domingo',
    'monday' => 'Segunda-feira',
    'tuesday' => 'Terça-feira',
    'wednesday' => 'Quarta-feira',
    'thursday' => 'Quinta-feira',
    'friday' => 'Sexta-feira',
    'saturday' => 'Sábado',

    // Additional Settings
    'additional_settings_section' => 'Configurações Adicionais',
    'use_ai_chatbot' => 'Usar Chatbot com IA',
    'default_language' => 'Idioma Padrão',
    'timezone' => 'Fuso Horário',

    // Days of the week (using numbers as keys)
    'days' => [
        '1' => 'Domingo',
        '2' => 'Segunda-feira',
        '3' => 'Terça-feira',
        '4' => 'Quarta-feira',
        '5' => 'Quinta-feira',
        '6' => 'Sexta-feira',
        '7' => 'Sábado',
    ],

    // Error messages
    'error' => [
        'show' => 'Erro ao carregar as configurações da unidade.',
        'edit_form' => 'Erro ao carregar o formulário de edição.',
        'update' => 'Erro ao atualizar as configurações da unidade.',
    ],

    // Success messages
    'success' => [
        'updated' => 'Configurações da unidade atualizadas com sucesso.',
    ],

    // Validation messages
    'validation' => [
        'sunday_start_required' => 'O horário de início do domingo é obrigatório quando o domingo está ativo.',
        'sunday_end_required' => 'O horário de término do domingo é obrigatório quando o domingo está ativo.',
        'monday_start_required' => 'O horário de início da segunda-feira é obrigatório quando a segunda-feira está ativa.',
        'monday_end_required' => 'O horário de término da segunda-feira é obrigatório quando a segunda-feira está ativa.',
        'tuesday_start_required' => 'O horário de início da terça-feira é obrigatório quando a terça-feira está ativa.',
        'tuesday_end_required' => 'O horário de término da terça-feira é obrigatório quando a terça-feira está ativa.',
        'wednesday_start_required' => 'O horário de início da quarta-feira é obrigatório quando a quarta-feira está ativa.',
        'wednesday_end_required' => 'O horário de término da quarta-feira é obrigatório quando a quarta-feira está ativa.',
        'thursday_start_required' => 'O horário de início da quinta-feira é obrigatório quando a quinta-feira está ativa.',
        'thursday_end_required' => 'O horário de término da quinta-feira é obrigatório quando a quinta-feira está ativa.',
        'friday_start_required' => 'O horário de início da sexta-feira é obrigatório quando a sexta-feira está ativa.',
        'friday_end_required' => 'O horário de término da sexta-feira é obrigatório quando a sexta-feira está ativa.',
        'saturday_start_required' => 'O horário de início do sábado é obrigatório quando o sábado está ativo.',
        'saturday_end_required' => 'O horário de término do sábado é obrigatório quando o sábado está ativo.',
    ],
];
