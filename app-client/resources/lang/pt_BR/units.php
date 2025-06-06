<?php

return [
    'title' => 'Unidades',
    'edit' => 'Editar Unidade',
    'name' => 'Nome',
    'active' => 'Ativo',
    'update' => 'Atualizar',
    'back' => 'Voltar',
    'details' => 'Detalhes da Unidade',
    'yes' => 'Sim',
    'no' => 'Não',
    'create' => 'Criar Unidade',
    'actions' => 'Ações',
    'confirm_deactivate' => 'Tem certeza que deseja desativar esta unidade?',
    'created_at' => 'Criado Em',
    'updated_at' => 'Atualizado Em',
    'validation' => [
        'name' => [
            'required' => 'O nome da unidade é obrigatório.',
            'string' => 'O nome da unidade deve ser um texto.',
            'max' => 'O nome da unidade não pode exceder 255 caracteres.',
        ],
        'active' => [
            'boolean' => 'O status ativo deve ser um valor booleano.',
        ],
    ],
    'attributes' => [
        'name' => 'nome',
        'active' => 'ativo',
    ],
    'success' => [
        'created' => 'Unidade criada com sucesso',
        'updated' => 'Unidade atualizada com sucesso',
        'deactivated' => 'Unidade desativada com sucesso',
    ],
    'error' => [
        'load' => 'Falha ao carregar unidades',
        'create_form' => 'Falha ao carregar formulário de criação',
        'create' => 'Falha ao criar unidade',
        'show' => 'Falha ao carregar detalhes da unidade',
        'edit_form' => 'Falha ao carregar formulário de edição',
        'update' => 'Falha ao atualizar unidade',
        'deactivate' => 'Falha ao desativar unidade',
    ],
    'settings' => 'Configurações',
];
