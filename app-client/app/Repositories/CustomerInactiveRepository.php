<?php

namespace App\Repositories;

use App\Enum\CustomerInactivePeriodEnum;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CustomerInactiveRepository
{
    public function getInactiveCustomers(int $days, int $unitId): Collection
    {
        return Customer::query()
            ->where('customers.unit_id', $unitId)
            ->where('customers.active', true)
            ->whereNotExists(function ($query) use ($days) {
                $query->select(DB::raw(1))
                    ->from('schedules')
                    ->whereColumn('schedules.customer_id', 'customers.id')
                    ->where('schedules.active', true)
                    ->where('schedules.schedule_date', '>=', now()->subDays($days));
            })
            ->orderBy('customers.name')
            ->get();
    }

    public function getInactiveCustomersCount(int $days, int $unitId): int
    {
        return Customer::query()
            ->where('customers.unit_id', $unitId)
            ->where('customers.active', true)
            ->whereNotExists(function ($query) use ($days) {
                $query->select(DB::raw(1))
                    ->from('schedules')
                    ->whereColumn('schedules.customer_id', 'customers.id')
                    ->where('schedules.active', true)
                    ->where('schedules.schedule_date', '>=', now()->subDays($days));
            })
            ->count();
    }

    public function getLastScheduleDate(Customer $customer): ?string
    {
        $lastSchedule = $customer->schedules()
            ->where('active', true)
            ->orderBy('schedule_date', 'desc')
            ->first();

        return $lastSchedule ? $lastSchedule->schedule_date->format('d/m/Y') : null;
    }

    public function getDaysSinceLastSchedule(Customer $customer): ?int
    {
        $lastSchedule = $customer->schedules()
            ->where('active', true)
            ->orderBy('schedule_date', 'desc')
            ->first();

        if (!$lastSchedule) {
            return null;
        }

        return now()->diffInDays($lastSchedule->schedule_date);
    }
}
