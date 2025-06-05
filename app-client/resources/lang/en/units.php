<?php

return [
    'title' => 'Units',
    'edit' => 'Edit Unit',
    'name' => 'Name',
    'active' => 'Active',
    'update' => 'Update',
    'back' => 'Back',
    'details' => 'Unit Details',
    'yes' => 'Yes',
    'no' => 'No',
    'create' => 'Create Unit',
    'actions' => 'Actions',
    'confirm_delete' => 'Are you sure you want to delete this unit?',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
    'validation' => [
        'name' => [
            'required' => 'The unit name is required.',
            'string' => 'The unit name must be a string.',
            'max' => 'The unit name cannot exceed 255 characters.',
        ],
        'active' => [
            'boolean' => 'The active status must be a boolean value.',
        ],
    ],
    'attributes' => [
        'name' => 'name',
        'active' => 'active',
    ],
    'success' => [
        'created' => 'Unit created successfully',
        'updated' => 'Unit updated successfully',
        'deleted' => 'Unit deleted successfully',
    ],
    'error' => [
        'load' => 'Failed to load units',
        'create_form' => 'Failed to load create form',
        'create' => 'Failed to create unit',
        'show' => 'Failed to load unit details',
        'edit_form' => 'Failed to load edit form',
        'update' => 'Failed to update unit',
        'delete' => 'Failed to delete unit',
    ],
    'settings' => 'Settings',
];
