<?php

return [
    'title' => 'Customers',
    'create' => 'Create Customer',
    'edit' => 'Edit Customer',
    'name' => 'Name',
    'phone' => 'Phone',
    'active' => 'Active',
    'back' => 'Back',
    'save_changes' => 'Save Changes',
    'yes' => 'Yes',
    'no' => 'No',
    'created_at' => 'Created At',
    'actions' => 'Actions',
    'view' => 'View',
    'delete' => 'Delete',
    'confirm_delete' => 'Are you sure you want to delete?',
    'select' => 'Select a customer',
    'success' => [
        'updated' => 'Customer updated successfully',
        'deleted' => 'Customer deleted successfully',
        'created' => 'Customer created successfully',
    ],
    'error' => [
        'update' => 'Failed to update customer',
        'delete' => 'Failed to delete customer',
        'load' => 'Failed to load customer for editing',
    ],
    'validation' => [
        'name' => [
            'required' => 'The customer name is required.',
            'string' => 'The customer name must be a string.',
            'max' => 'The customer name cannot exceed 120 characters.',
        ],
        'phone' => [
            'string' => 'The phone must be a string.',
            'max' => 'The phone cannot exceed 20 characters.',
        ],
        'active' => [
            'boolean' => 'The active status must be true or false.',
        ],
    ],
];
