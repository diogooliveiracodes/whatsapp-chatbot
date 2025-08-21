<?php

return [
    'title' => 'Dashboard',
    'welcome_message' => "You're logged in!",
    'owner' => [
        'insights' => [
            'title' => 'Company overview',
            'description' => 'Key numbers in real-time',
        ],
        'kpis' => [
            'schedules_today' => 'Schedules Today',
            'schedules_month' => 'Schedules This Month',
            'schedules_year' => 'Schedules This Year',
            'cancellations_today' => 'Cancellations Today',
            'cancellations_month' => 'Cancellations This Month',
            'cancellations_year' => 'Cancellations This Year',
            'payments_received' => 'Payments Received',
            'payments_receivable' => 'Payments Receivable',
            'schedules_pending' => 'Pending Schedules',
        ],
        'charts' => [
            'schedules_by_month' => 'Schedules by Month (Last 12 months)',
            'schedules_by_weekday_30d' => 'Schedules by Weekday (Last 30 days)',
            'payments_by_month' => 'Payments by Month',
            'cancellations_by_month' => 'Cancellations by Month',
        ],
    ],
    'schedule_link' => [
        'title' => 'Schedule Link',
        'label' => 'Public scheduling link',
        'copy_button' => 'Copy',
        'copied_message' => 'Copied!',
        'error_message' => 'Error',
        'description' => 'Share this link with your customers so they can schedule appointments directly.',
    ],
];
