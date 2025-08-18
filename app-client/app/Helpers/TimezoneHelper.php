<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimezoneHelper
{
    /**
     * Convert UTC time to user timezone for display
     *
     * @param string|null $time Time in H:i format (stored in UTC)
     * @param string|null $userTimezone User timezone (defaults to America/Sao_Paulo)
     * @param string|null $referenceDate Reference date for conversion (defaults to today)
     * @return string|null Time in H:i format in user timezone
     */
    public static function convertTimeFromUtc(?string $time, ?string $userTimezone = null, ?string $referenceDate = null): ?string
    {
        if (!$time) {
            return null;
        }

        $userTimezone = $userTimezone ?? 'America/Sao_Paulo';
        $referenceDate = $referenceDate ?? now()->format('Y-m-d');

        try {
            $utcTime = Carbon::parse($referenceDate . ' ' . $time, 'UTC');
            $localTime = $utcTime->setTimezone($userTimezone);
            return $localTime->format('H:i');
        } catch (\Exception $e) {
            return $time; // Return original if conversion fails
        }
    }

    /**
     * Convert user timezone time to UTC for storage
     *
     * @param string|null $time Time in H:i format (in user timezone)
     * @param string|null $userTimezone User timezone (defaults to America/Sao_Paulo)
     * @param string|null $referenceDate Reference date for conversion (defaults to today)
     * @return string|null Time in H:i format in UTC
     */
    public static function convertTimeToUtc(?string $time, ?string $userTimezone = null, ?string $referenceDate = null): ?string
    {
        if (!$time) {
            return null;
        }

        $userTimezone = $userTimezone ?? 'America/Sao_Paulo';
        $referenceDate = $referenceDate ?? now()->format('Y-m-d');

        try {
            $localTime = Carbon::parse($referenceDate . ' ' . $time . ':00', $userTimezone);
            $utcTime = $localTime->setTimezone('UTC');
            return $utcTime->format('H:i');
        } catch (\Exception $e) {
            return $time; // Return original if conversion fails
        }
    }

    /**
     * Convert UTC datetime to user timezone
     *
     * @param string $datetime Datetime in Y-m-d H:i:s format (stored in UTC)
     * @param string|null $userTimezone User timezone (defaults to America/Sao_Paulo)
     * @return Carbon|null Carbon instance in user timezone
     */
    public static function convertDateTimeFromUtc(string $datetime, ?string $userTimezone = null): ?Carbon
    {
        if (!$datetime) {
            return null;
        }

        $userTimezone = $userTimezone ?? 'America/Sao_Paulo';

        try {
            $utcDateTime = Carbon::parse($datetime, 'UTC');
            return $utcDateTime->setTimezone($userTimezone);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Convert user timezone datetime to UTC
     *
     * @param string $datetime Datetime in Y-m-d H:i:s format (in user timezone)
     * @param string|null $userTimezone User timezone (defaults to America/Sao_Paulo)
     * @return Carbon|null Carbon instance in UTC
     */
    public static function convertDateTimeToUtc(string $datetime, ?string $userTimezone = null): ?Carbon
    {
        if (!$datetime) {
            return null;
        }

        $userTimezone = $userTimezone ?? 'America/Sao_Paulo';

        try {
            $localDateTime = Carbon::parse($datetime, $userTimezone);
            return $localDateTime->setTimezone('UTC');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get current time in user timezone
     *
     * @param string|null $userTimezone User timezone (defaults to America/Sao_Paulo)
     * @return Carbon Current time in user timezone
     */
    public static function nowInUserTimezone(?string $userTimezone = null): Carbon
    {
        $userTimezone = $userTimezone ?? 'America/Sao_Paulo';
        return now()->setTimezone($userTimezone);
    }

    /**
     * Parse date with timezone consideration
     *
     * @param mixed $date Date to parse
     * @param string|null $userTimezone User timezone (defaults to America/Sao_Paulo)
     * @return Carbon Carbon instance in user timezone
     */
    public static function parseDateWithTimezone($date, ?string $userTimezone = null): Carbon
    {
        $userTimezone = $userTimezone ?? 'America/Sao_Paulo';

        if ($date instanceof Carbon) {
            return $date->setTimezone($userTimezone);
        }

        return Carbon::parse($date, $userTimezone);
    }
}
