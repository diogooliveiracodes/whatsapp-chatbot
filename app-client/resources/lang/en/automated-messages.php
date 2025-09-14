<?php

return [
    'automated_messages' => 'Automated Messages',
    'automated_message' => 'Automated Message',
    'new_automated_message' => 'New Automated Message',
    'edit_automated_message' => 'Edit Automated Message',
    'create' => 'Create Message',
    'update' => 'Update Message',
    'delete' => 'Delete Message',
    'back' => 'Back',
    'actions' => 'Actions',
    'no_messages' => 'No automated messages found',
    'no_messages_description' => 'Start by creating your first automated message to improve communication with your customers.',

    'fields' => [
        'name' => 'Name',
        'type' => 'Type',
        'content' => 'Message Content',

        'unit' => 'Unit',
        'created_by' => 'Created by',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
    ],

    'types' => [
        'schedule_confirmation' => 'Schedule Confirmation',
        'schedule_reminder' => 'Schedule Reminder',
        'schedule_cancellation' => 'Schedule Cancellation',
        'schedule_reschedule' => 'Schedule Reschedule',
        'payment_confirmation' => 'Payment Confirmation',
        'payment_reminder' => 'Payment Reminder',
        'welcome_message' => 'Welcome Message',
        'custom_message' => 'Custom Message',
    ],

    'descriptions' => [
        'schedule_confirmation' => 'Sent when a schedule is confirmed',
        'schedule_reminder' => 'Sent as a reminder before the schedule',
        'schedule_cancellation' => 'Sent when a schedule is cancelled',
        'schedule_reschedule' => 'Sent when a schedule is rescheduled',
        'payment_confirmation' => 'Sent when a payment is confirmed',
        'payment_reminder' => 'Sent as a payment reminder',
        'welcome_message' => 'Sent to new customers',
        'custom_message' => 'Custom message for specific use',
    ],

    'templates' => [
        'schedule_confirmation' => 'Hello {customer_name}! Your appointment is confirmed for {schedule_date} at {schedule_time} for the service {service_name} with the professional {unit_name}. If you have any questions, reply to this message.',
        'schedule_reminder' => 'Reminder: {customer_name}, you have an appointment on {schedule_date} at {schedule_time} for the service {service_name} with the professional {unit_name}. If you need to reschedule, let us know.',
        'schedule_cancellation' => 'Hello {customer_name}, your appointment for {service_name} on {schedule_date} at {schedule_time} was canceled. If you wish, we can book a new date.',
        'schedule_reschedule' => 'Hello {customer_name}, your appointment for {service_name} has been rescheduled to {schedule_date} at {schedule_time} with the professional {unit_name}.',
        'payment_confirmation' => 'Payment confirmed! We received {payment_amount} via {payment_method}. Thank you for choosing {company_name}.',
        'payment_reminder' => 'Hello {customer_name}, we identified a pending payment of {payment_amount}. If you have already paid, please disregard. Questions? Reply to this message.',
        'welcome_message' => 'Welcome, {customer_name}! This is {company_name}. We are available to help. How can we assist you today?',
        'custom_message' => 'Custom message: edit this content as needed.',
    ],

    'messages' => [
        'created' => 'Automated message created successfully!',
        'updated' => 'Automated message updated successfully!',
        'deleted' => 'Automated message deleted successfully!',

        'validation_error' => 'Validation error',
        'create_error' => 'Error creating automated message: :message',
        'update_error' => 'Error updating automated message: :message',
        'delete_error' => 'Error deleting automated message: :message',
        'load_error' => 'Error loading automated messages.',
        'name_required' => 'Name is required',
        'name_max' => 'Name cannot have more than 255 characters',
        'type_required' => 'Type is required',
        'type_invalid' => 'Invalid type',
        'content_required' => 'Message content is required',
        'content_max' => 'Message content cannot have more than 1000 characters',
        'unit_required' => 'Unit is required',
        'unit_not_found' => 'Selected unit does not exist',
        'unit_not_belongs_to_company' => 'Unit does not belong to your company',
        'confirm_delete' => 'Are you sure you want to delete this automated message?',
        'created_description' => 'The automated message was created successfully and is available for use.',
        'updated_description' => 'The automated message was updated successfully.',
    ],

    'placeholders' => [
        'name' => 'Enter message name',
        'content' => 'Enter message content...',
        'search' => 'Search messages...',
    ],

    'variables' => [
        'title' => 'Available Variables',
        'description' => 'Use the following variables in the message content:',
        'customer_name' => '{customer_name} - Customer name',
        'customer_phone' => '{customer_phone} - Customer phone',
        'schedule_date' => '{schedule_date} - Schedule date',
        'schedule_time' => '{schedule_time} - Schedule time',
        'service_name' => '{service_name} - Service name',
        'unit_name' => '{unit_name} - Unit name',
        'company_name' => '{company_name} - Company name',
        'payment_amount' => '{payment_amount} - Payment amount',
        'payment_method' => '{payment_method} - Payment method',
    ],



    'unit_selection' => 'Unit Selection',
    'show_unit_selector' => 'Show unit selector',
    'filter_by_unit' => 'Filter by unit',
    'all_units' => 'All units',

    // Modal translations
    'select_message' => 'Select Message',
    'send_message' => 'Send Message',
    'loading_messages' => 'Loading messages...',
    'no_messages_available' => 'No messages available',
    'create_message_first' => 'Create an automated message first to be able to send it.',
    'create_message' => 'Create Message',
    'cancel' => 'Cancel',
    'invalid_phone' => 'Invalid or missing customer phone number.',
];
