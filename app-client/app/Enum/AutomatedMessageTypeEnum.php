<?php

namespace App\Enum;

enum AutomatedMessageTypeEnum: string
{
    case SCHEDULE_CONFIRMATION = 'schedule_confirmation';
    case SCHEDULE_REMINDER = 'schedule_reminder';
    case SCHEDULE_CANCELLATION = 'schedule_cancellation';
    case SCHEDULE_RESCHEDULE = 'schedule_reschedule';
    case PAYMENT_CONFIRMATION = 'payment_confirmation';
    case PAYMENT_REMINDER = 'payment_reminder';
    case WELCOME_MESSAGE = 'welcome_message';
    case CUSTOM_MESSAGE = 'custom_message';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): string
    {
        return match($this) {
            self::SCHEDULE_CONFIRMATION => __('automated-messages.types.schedule_confirmation'),
            self::SCHEDULE_REMINDER => __('automated-messages.types.schedule_reminder'),
            self::SCHEDULE_CANCELLATION => __('automated-messages.types.schedule_cancellation'),
            self::SCHEDULE_RESCHEDULE => __('automated-messages.types.schedule_reschedule'),
            self::PAYMENT_CONFIRMATION => __('automated-messages.types.payment_confirmation'),
            self::PAYMENT_REMINDER => __('automated-messages.types.payment_reminder'),
            self::WELCOME_MESSAGE => __('automated-messages.types.welcome_message'),
            self::CUSTOM_MESSAGE => __('automated-messages.types.custom_message'),
        };
    }

    public function getDescription(): string
    {
        return match($this) {
            self::SCHEDULE_CONFIRMATION => __('automated-messages.descriptions.schedule_confirmation'),
            self::SCHEDULE_REMINDER => __('automated-messages.descriptions.schedule_reminder'),
            self::SCHEDULE_CANCELLATION => __('automated-messages.descriptions.schedule_cancellation'),
            self::SCHEDULE_RESCHEDULE => __('automated-messages.descriptions.schedule_reschedule'),
            self::PAYMENT_CONFIRMATION => __('automated-messages.descriptions.payment_confirmation'),
            self::PAYMENT_REMINDER => __('automated-messages.descriptions.payment_reminder'),
            self::WELCOME_MESSAGE => __('automated-messages.descriptions.welcome_message'),
            self::CUSTOM_MESSAGE => __('automated-messages.descriptions.custom_message'),
        };
    }
}
