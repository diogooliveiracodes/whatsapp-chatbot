<?php

return [
    'title' => 'Clientes',
    'create' => 'Criar Cliente',
    'edit' => 'Editar Cliente',
    'name' => 'Nome',
    'phone' => 'Telefone',
    'active' => 'Ativo',
    'inactive' => 'Inativo',
    'back' => 'Voltar',
    'save_changes' => 'Salvar Alterações',
    'yes' => 'Sim',
    'no' => 'Não',
    'created_at' => 'Criado em',
    'actions' => 'Ações',
    'view' => 'Visualizar',
    'delete' => 'Excluir',
    'confirm_delete' => 'Tem certeza que deseja excluir?',
    'select' => 'Selecione um cliente',
    'success' => [
        'updated' => 'Cliente atualizado com sucesso',
        'deleted' => 'Cliente excluído com sucesso',
        'created' => 'Cliente criado com sucesso',
    ],
    'error' => [
        'update' => 'Falha ao atualizar cliente',
        'delete' => 'Falha ao excluir cliente',
        'load' => 'Falha ao carregar cliente para edição',
    ],
    'validation' => [
        'name' => [
            'required' => 'O nome do cliente é obrigatório.',
            'string' => 'O nome do cliente deve ser um texto.',
            'max' => 'O nome do cliente não pode exceder 120 caracteres.',
        ],
        'phone' => [
            'string' => 'O telefone deve ser um texto.',
            'max' => 'O telefone não pode exceder 20 caracteres.',
            'format' => 'O telefone deve estar no formato (99) 99999-9999 ou (99) 9999-9999.',
        ],
        'active' => [
            'boolean' => 'O status ativo deve ser verdadeiro ou falso.',
        ],
    ],
    'search' => 'Buscar',
    'search_placeholder' => 'Buscar por nome ou telefone',
    'no_customers_found' => 'Nenhum cliente encontrado',
    'no_customers_description' => 'Não há clientes cadastrados no momento. Clique em "Criar Cliente" para adicionar um novo cliente.',
];
