<?php

return [
    'title' => 'Unit Settings',
    'edit' => 'Edit Unit Settings',
    'back' => 'Back',
    'save_changes' => 'Save Changes',
    'yes' => 'Yes',
    'no' => 'No',

    // Basic Information
    'basic_info_section' => 'Basic Information',
    'name' => 'Name',
    'phone' => 'Phone',

    // Address Information
    'address_section' => 'Address',
    'street' => 'Street',
    'number' => 'Number',
    'complement' => 'Complement',
    'neighborhood' => 'Neighborhood',
    'city' => 'City',
    'state' => 'State',
    'zipcode' => 'ZIP Code',

    // WhatsApp Configuration
    'whatsapp_section' => 'WhatsApp Settings',
    'whatsapp_webhook_url' => 'WhatsApp Webhook URL',
    'whatsapp_number' => 'WhatsApp Number',

    // Working Hours
    'working_hours_section' => 'Working Hours',
    'sunday_start' => 'Start Time (Sunday)',
    'sunday_end' => 'End Time (Sunday)',
    'monday_start' => 'Start Time (Monday)',
    'monday_end' => 'End Time (Monday)',
    'tuesday_start' => 'Start Time (Tuesday)',
    'tuesday_end' => 'End Time (Tuesday)',
    'wednesday_start' => 'Start Time (Wednesday)',
    'wednesday_end' => 'End Time (Wednesday)',
    'thursday_start' => 'Start Time (Thursday)',
    'thursday_end' => 'End Time (Thursday)',
    'friday_start' => 'Start Time (Friday)',
    'friday_end' => 'End Time (Friday)',
    'saturday_start' => 'Start Time (Saturday)',
    'saturday_end' => 'End Time (Saturday)',
    'working_days' => 'Working Days',
    'sunday' => 'Sunday',
    'monday' => 'Monday',
    'tuesday' => 'Tuesday',
    'wednesday' => 'Wednesday',
    'thursday' => 'Thursday',
    'friday' => 'Friday',
    'saturday' => 'Saturday',

    // Additional Settings
    'additional_settings_section' => 'Additional Settings',
    'use_ai_chatbot' => 'Use AI Chatbot',
    'default_language' => 'Default Language',
    'timezone' => 'Timezone',

    // Days of the week (using numbers as keys)
    'days' => [
        '1' => 'Sunday',
        '2' => 'Monday',
        '3' => 'Tuesday',
        '4' => 'Wednesday',
        '5' => 'Thursday',
        '6' => 'Friday',
        '7' => 'Saturday',
    ],

    // Error messages
    'error' => [
        'show' => 'Error loading unit settings.',
        'edit_form' => 'Error loading edit form.',
        'update' => 'Error updating unit settings.',
    ],

    // Success messages
    'success' => [
        'updated' => 'Unit settings updated successfully.',
    ],

    // Validation messages
    'validation' => [
        'sunday_start_required' => 'The start time for Sunday is required when Sunday is active.',
        'sunday_end_required' => 'The end time for Sunday is required when Sunday is active.',
        'monday_start_required' => 'The start time for Monday is required when Monday is active.',
        'monday_end_required' => 'The end time for Monday is required when Monday is active.',
        'tuesday_start_required' => 'The start time for Tuesday is required when Tuesday is active.',
        'tuesday_end_required' => 'The end time for Tuesday is required when Tuesday is active.',
        'wednesday_start_required' => 'The start time for Wednesday is required when Wednesday is active.',
        'wednesday_end_required' => 'The end time for Wednesday is required when Wednesday is active.',
        'thursday_start_required' => 'The start time for Thursday is required when Thursday is active.',
        'thursday_end_required' => 'The end time for Thursday is required when Thursday is active.',
        'friday_start_required' => 'The start time for Friday is required when Friday is active.',
        'friday_end_required' => 'The end time for Friday is required when Friday is active.',
        'saturday_start_required' => 'The start time for Saturday is required when Saturday is active.',
        'saturday_end_required' => 'The end time for Saturday is required when Saturday is active.',
    ],
];
