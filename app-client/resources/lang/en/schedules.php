<?php

return [
    'messages' => [
        'closed' => 'Closed',
        'outside_working_days' => 'The appointment cannot be scheduled outside working days.',
        'outside_working_hours' => 'The appointment must be within business hours.',
        'time_conflict' => 'There is already an appointment at this time.',
        'created' => 'Appointment created successfully!',
        'updated' => 'Appointment updated successfully!',
        'deleted' => 'Appointment deleted successfully!',
        'cancelled' => 'Appointment cancelled successfully!',
        'validation_error' => 'Validation error',
        'create_error' => 'Error creating appointment: :message',
        'update_error' => 'Error updating appointment: :message',
        'delete_error' => 'Error deleting appointment: :message',
        'cancel_error' => 'Error cancelling appointment: :message',
        'load_error' => 'Error loading schedules.',
        'customer_required' => 'Customer is required',
        'customer_not_found' => 'Selected customer does not exist',
        'date_required' => 'Schedule date is required',
        'invalid_date' => 'Invalid date format',
        'start_time_required' => 'Start time is required',
        'end_time_required' => 'End time is required',
        'invalid_time_format' => 'Invalid time format. Use HH:mm format',
        'end_time_after_start' => 'End time must be after start time',
        'service_type_required' => 'Service type is required',
        'confirm_delete' => 'Are you sure you want to delete this appointment?',
    ],
    'today' => 'Today',
    'previous_week' => 'Previous Week',
    'next_week' => 'Next Week',
    'new_schedule' => 'New Schedule',
    'edit_schedule' => 'Edit Schedule',
    'date' => 'Date',
    'start_time' => 'Start Time',
    'end_time' => 'End Time',
    'service_type' => 'Service Type',
    'status' => 'Status',
    'statuses' => [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
    ],
    'notes' => 'Notes',
    'back' => 'Back',
    'create' => 'Create Schedule',
    'update' => 'Update Schedule',
];
