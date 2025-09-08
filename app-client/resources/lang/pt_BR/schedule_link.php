<?php

return [
    'choose_unit' => 'Escolha a unidade para o atendimento',
    'back' => 'Voltar',
    'title' => 'Agendar atendimento em :unit',
    'name' => 'Seu nome',
    'phone' => 'Seu telefone (WhatsApp)',
    'document_number' => 'CPF/CNPJ',
    'document_required_for_pix' => 'Necessário para gerar o código PIX',
    'document_number_required' => 'Por favor, informe seu CPF/CNPJ para gerar o código PIX.',
    'select_service' => 'Selecione o tipo de serviço',
    'choose_day' => 'Escolha o dia',
    'choose_time' => 'Escolha o horário',
    'success_title' => 'Agendamento realizado!',
    'success_message' => 'Seu agendamento foi criado com sucesso. Você receberá informações no WhatsApp se sua unidade utilizar este recurso.',
    'book_another' => 'Agendar outro horário',

    // Payment section
    'payment_section_title' => 'Pagamento do Agendamento',
    'payment_amount' => 'R$ :amount',
    'generate_pix' => 'Gerar PIX',
    'generating_pix' => 'Gerando código PIX...',
    'pix_code_label' => 'Código PIX (Copia e Cola)',
    'copy_pix' => 'Copiar',
    'pix_instructions' => 'Copie o código PIX e cole no seu aplicativo bancário para realizar o pagamento.',

    // Messages
    'schedule_not_found' => 'Agendamento não encontrado.',
    'pix_generation_error' => 'Erro ao gerar código PIX. Tente novamente.',
    'pix_generated_success' => 'Código PIX gerado com sucesso!',
    'pix_code_not_found' => 'Código PIX não encontrado na resposta.',
    'pix_code_error' => 'Erro ao obter código PIX.',
    'pix_copied_success' => 'Código PIX copiado para a área de transferência!',
    'pix_copy_error' => 'Erro ao copiar código PIX.',
    'payment_id_not_found' => 'ID do pagamento não encontrado.',

    // Payment status
    'check_payment_status' => 'Verificar Status do Pagamento',
    'payment_pending' => 'Pagamento Pendente',
    'payment_pending_message' => 'Aguardando confirmação do pagamento. Clique em "Verificar Status" para atualizar.',
    'payment_confirmed' => 'Pagamento confirmado com sucesso!',
    'payment_still_pending' => 'Pagamento ainda está pendente.',
    'payment_overdue_message' => 'O pagamento venceu. Você será redirecionado para fazer um novo agendamento.',
    'payment_rejected_message' => 'O pagamento foi rejeitado. Tente gerar um novo código PIX.',
    'payment_status_paid' => 'Pagamento Confirmado',
    'payment_status_rejected' => 'Pagamento Rejeitado',
    'payment_status_overdue' => 'Pagamento em Atraso',
    'payment_not_found' => 'Pagamento não encontrado.',
    'generate_new_pix' => 'Gerar Novo PIX',
    'payment_failed_new_pix_available' => 'Pagamento anterior falhou. Você pode gerar um novo código PIX.',

    'messages' => [
        'created' => 'Agendamento criado com sucesso.',
        'unexpected_error' => 'Ocorreu um erro inesperado ao criar o agendamento.',
        'no_user_available' => 'Não há usuário disponível para receber o agendamento nesta unidade.',
    ],
];
