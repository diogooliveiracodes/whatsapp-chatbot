<?php

return [
    'choose_unit' => 'Choose a unit for your appointment',
    'back' => 'Back',
    'title' => 'Book an appointment with :unit',
    'name' => 'Your name',
    'phone' => 'Your phone (WhatsApp)',
    'document_number' => 'CPF/CNPJ',
    'document_required_for_pix' => 'Required to generate PIX code',
    'document_number_required' => 'Please provide your CPF/CNPJ to generate the PIX code.',
    'select_service' => 'Select a service type',
    'choose_day' => 'Choose a day',
    'choose_time' => 'Choose a time',
    'success_title' => 'Appointment scheduled!',
    'success_message' => 'Your appointment has been created successfully. You may receive updates on WhatsApp if this unit uses that feature.',
    'book_another' => 'Book another time',

    // Payment section
    'payment_section_title' => 'Appointment Payment',
    'payment_amount' => '$:amount',
    'generate_pix' => 'Generate PIX',
    'generating_pix' => 'Generating PIX code...',
    'pix_code_label' => 'PIX Code (Copy and Paste)',
    'copy_pix' => 'Copy',
    'pix_instructions' => 'Copy the PIX code and paste it into your banking app to make the payment.',
    'payment_method_label' => 'Payment method',
    'method_pix' => 'Pix',
    'method_cash' => 'Cash',
    'credit_card_soon' => 'Credit Card (coming soon)',
    'debit_card_soon' => 'Debit Card (coming soon)',
    'cash_info_text' => 'Confirm your appointment to pay in cash on site.',
    'confirm_cash' => 'Confirm appointment',

    // Messages
    'schedule_not_found' => 'Schedule not found.',
    'pix_generation_error' => 'Error generating PIX code. Please try again.',
    'pix_generated_success' => 'PIX code generated successfully!',
    'pix_code_not_found' => 'PIX code not found in response.',
    'pix_code_error' => 'Error getting PIX code.',
    'pix_copied_success' => 'PIX code copied to clipboard!',
    'pix_copy_error' => 'Error copying PIX code.',
    'payment_id_not_found' => 'Payment ID not found.',

    // Payment status
    'check_payment_status' => 'Check Payment Status',
    'payment_pending' => 'Payment Pending',
    'payment_pending_message' => 'Waiting for payment confirmation. Click "Check Status" to update.',
    'payment_confirmed' => 'Payment confirmed successfully!',
    'payment_still_pending' => 'Payment is still pending.',
    'payment_overdue_message' => 'The payment has expired. You will be redirected to make a new appointment.',
    'payment_rejected_message' => 'Payment was rejected. Try generating a new PIX code.',
    'payment_status_paid' => 'Payment Confirmed',
    'payment_status_rejected' => 'Payment Rejected',
    'payment_status_overdue' => 'Payment Overdue',
    'payment_not_found' => 'Payment not found.',
    'generate_new_pix' => 'Generate New PIX',
    'payment_failed_new_pix_available' => 'Previous payment failed. You can generate a new PIX code.',

    'messages' => [
        'created' => 'Schedule created successfully.',
        'unexpected_error' => 'An unexpected error occurred while creating the schedule.',
        'no_user_available' => 'There is no available user in this unit to receive the appointment.',
    ],

    // Labels
    'customer_label' => 'Customer',
    'service_label' => 'Service',
    'unit_label' => 'Unit',
    'status_label' => 'Status',
    'notes_label' => 'Notes',
    'professional_label' => 'Professional',
];
