<?php

return [
    'unit' => 'Unit',
    'unitServiceType' => 'Service Type',
    'unitName' => 'Unit Name',
    'title' => 'Service Types',
    'create' => 'Create Service Type',
    'edit' => 'Edit Service Type',
    'name' => 'Name',
    'description' => 'Description',
    'active' => 'Active',
    'back' => 'Back',
    'save_changes' => 'Save Changes',
    'yes' => 'Yes',
    'no' => 'No',
    'created_at' => 'Created At',
    'actions' => 'Actions',
    'view' => 'View',
    'delete' => 'Delete',
    'confirm_delete' => 'Are you sure you want to delete this service type?',
    'success' => [
        'created' => 'Service type created successfully',
        'updated' => 'Service type updated successfully',
        'deleted' => 'Service type deleted successfully',
    ],
    'error' => [
        'load' => 'Failed to load service types',
        'create' => 'Failed to create service type',
        'update' => 'Failed to update service type',
        'delete' => 'Failed to delete service type',
    ],
    'validation' => [
        'name' => [
            'required' => 'The service type name is required.',
            'string' => 'The service type name must be a string.',
            'max' => 'The service type name cannot exceed 100 characters.',
            'unique' => 'This service type name is already in use.',
        ],
        'description' => [
            'string' => 'The description must be a string.',
            'max' => 'The description cannot exceed 255 characters.',
        ],
        'active' => [
            'boolean' => 'The active status must be a boolean value.',
        ],
    ],
    'attributes' => [
        'name' => 'name',
        'description' => 'description',
        'active' => 'active',
    ],
];
