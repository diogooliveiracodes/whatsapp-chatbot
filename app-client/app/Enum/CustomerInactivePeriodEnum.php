<?php

namespace App\Enum;

enum CustomerInactivePeriodEnum: int
{
    case FIFTEEN_DAYS = 15;
    case THIRTY_DAYS = 30;
    case FORTY_FIVE_DAYS = 45;
    case SIXTY_DAYS = 60;
    case NINETY_DAYS = 90;

    public function getLabel(): string
    {
        return match($this) {
            self::FIFTEEN_DAYS => __('enums.customer_inactive_period.fifteen_days'),
            self::THIRTY_DAYS => __('enums.customer_inactive_period.thirty_days'),
            self::FORTY_FIVE_DAYS => __('enums.customer_inactive_period.forty_five_days'),
            self::SIXTY_DAYS => __('enums.customer_inactive_period.sixty_days'),
            self::NINETY_DAYS => __('enums.customer_inactive_period.ninety_days'),
        };
    }

    public function getDays(): int
    {
        return $this->value;
    }

    public static function getOptions(): array
    {
        return [
            self::FIFTEEN_DAYS->value => self::FIFTEEN_DAYS->getLabel(),
            self::THIRTY_DAYS->value => self::THIRTY_DAYS->getLabel(),
            self::FORTY_FIVE_DAYS->value => self::FORTY_FIVE_DAYS->getLabel(),
            self::SIXTY_DAYS->value => self::SIXTY_DAYS->getLabel(),
            self::NINETY_DAYS->value => self::NINETY_DAYS->getLabel(),
        ];
    }
}
