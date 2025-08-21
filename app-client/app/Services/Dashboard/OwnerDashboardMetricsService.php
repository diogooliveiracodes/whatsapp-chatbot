<?php

namespace App\Services\Dashboard;

use App\Enum\PaymentStatusEnum;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class OwnerDashboardMetricsService
{
    private array $cache = [];

    public function getMetricsForOwner(User $owner): array
    {
        $companyId = $owner->company_id;

        if (isset($this->cache[$companyId])) {
            return $this->cache[$companyId];
        }

        // KPI time ranges (by appointment date schedule_date)
        $todayStart = Carbon::today();
        $todayEnd = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        [$schedulesDay, $schedulesMonth, $schedulesYear, $cancellationsDay, $cancellationsMonth, $cancellationsYear]
            = $this->computeKpiCountsByScheduleDate($companyId, $todayStart, $todayEnd, $startOfMonth, $startOfYear);

        // Payments
        $paymentsReceivedTotal = $this->sumPayments($companyId, [PaymentStatusEnum::PAID]);
        $paymentsReceivableTotal = $this->sumPayments($companyId, [PaymentStatusEnum::PENDING, PaymentStatusEnum::OVERDUE]);

        // Charts
        $charts = [
            'schedules_by_month' => $this->schedulesByMonth($companyId, months: 12),
            'schedules_by_weekday_30d' => $this->schedulesByWeekdayLast30Days($companyId),
            'payments_by_month' => $this->paymentsByMonth($companyId, months: 12),
            'cancellations_by_month' => $this->cancellationsByMonth($companyId, months: 12),
        ];

        $result = [
            'kpis' => [
                'schedules' => [
                    'day' => $schedulesDay,
                    'month' => $schedulesMonth,
                    'year' => $schedulesYear,
                    'pending' => $this->countPendingSchedules($companyId),
                ],
                'cancellations' => [
                    'day' => $cancellationsDay,
                    'month' => $cancellationsMonth,
                    'year' => $cancellationsYear,
                ],
                'payments' => [
                    'received_total' => (float) $paymentsReceivedTotal,
                    'receivable_total' => (float) $paymentsReceivableTotal,
                ],
            ],
            'charts' => $charts,
        ];

        // cache for this request lifecycle
        $this->cache[$companyId] = $result;
        return $result;
    }

    private function countPendingSchedules(int $companyId): int
    {
        return (int) Schedule::query()
            ->whereHas('unit', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->where('status', 'pending')
            ->count();
    }

    private function countSchedulesByScheduleDate(int $companyId, Carbon $start, Carbon $end, bool $excludeCancelled = false, bool $cancelledOnly = false): int
    {
        $query = Schedule::query()
            ->whereHas('unit', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->whereBetween('schedule_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);

        if ($excludeCancelled) {
            $query->where('status', '!=', 'cancelled');
        }

        if ($cancelledOnly) {
            $query->where('status', 'cancelled');
        }

        return (int) $query->count();
    }

    private function computeKpiCountsByScheduleDate(int $companyId, Carbon $todayStart, Carbon $todayEnd, Carbon $startOfMonth, Carbon $startOfYear): array
    {
        $rows = Schedule::query()
            ->selectRaw('SUM(CASE WHEN schedule_date = ? AND status != ? THEN 1 ELSE 0 END) as schedules_day', [
                $todayStart->format('Y-m-d'), 'cancelled'
            ])
            ->selectRaw('SUM(CASE WHEN schedule_date >= ? AND status != ? THEN 1 ELSE 0 END) as schedules_month', [
                $startOfMonth->format('Y-m-d'), 'cancelled'
            ])
            ->selectRaw('SUM(CASE WHEN schedule_date >= ? AND status != ? THEN 1 ELSE 0 END) as schedules_year', [
                $startOfYear->format('Y-m-d'), 'cancelled'
            ])
            ->selectRaw('SUM(CASE WHEN schedule_date = ? AND status = ? THEN 1 ELSE 0 END) as cancellations_day', [
                $todayStart->format('Y-m-d'), 'cancelled'
            ])
            ->selectRaw('SUM(CASE WHEN schedule_date >= ? AND status = ? THEN 1 ELSE 0 END) as cancellations_month', [
                $startOfMonth->format('Y-m-d'), 'cancelled'
            ])
            ->selectRaw('SUM(CASE WHEN schedule_date >= ? AND status = ? THEN 1 ELSE 0 END) as cancellations_year', [
                $startOfYear->format('Y-m-d'), 'cancelled'
            ])
            ->whereHas('unit', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->first();

        return [
            (int) ($rows->schedules_day ?? 0),
            (int) ($rows->schedules_month ?? 0),
            (int) ($rows->schedules_year ?? 0),
            (int) ($rows->cancellations_day ?? 0),
            (int) ($rows->cancellations_month ?? 0),
            (int) ($rows->cancellations_year ?? 0),
        ];
    }

    private function sumPayments(int $companyId, array $statuses): float
    {
        return (float) Payment::query()
            ->where('company_id', $companyId)
            ->whereIn('status', array_map(fn ($s) => $s->value, $statuses))
            ->sum('amount');
    }

    private function schedulesByMonth(int $companyId, int $months = 12): array
    {
        $start = Carbon::now()->startOfMonth()->subMonths($months - 1);
        $end = Carbon::now()->endOfMonth();

        $rows = Schedule::query()
            ->selectRaw('YEAR(schedule_date) as y, MONTH(schedule_date) as m, COUNT(*) as total')
            ->whereHas('unit', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->whereBetween('schedule_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->where('status', '!=', 'cancelled')
            ->groupBy('y', 'm')
            ->orderBy('y')
            ->orderBy('m')
            ->get();

        $map = [];
        foreach ($rows as $r) {
            $key = sprintf('%04d-%02d', $r->y, $r->m);
            $map[$key] = (int) $r->total;
        }

        $labels = [];
        $data = [];
        $cursor = $start->copy();
        for ($i = 0; $i < $months; $i++) {
            $key = $cursor->format('Y-m');
            $labels[] = $cursor->format('M/Y');
            $data[] = $map[$key] ?? 0;
            $cursor->addMonth();
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function cancellationsByMonth(int $companyId, int $months = 12): array
    {
        $start = Carbon::now()->startOfMonth()->subMonths($months - 1);
        $end = Carbon::now()->endOfMonth();

        $rows = Schedule::query()
            ->selectRaw('YEAR(schedule_date) as y, MONTH(schedule_date) as m, COUNT(*) as total')
            ->whereHas('unit', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->whereBetween('schedule_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->where('status', 'cancelled')
            ->groupBy('y', 'm')
            ->orderBy('y')
            ->orderBy('m')
            ->get();

        $map = [];
        foreach ($rows as $r) {
            $key = sprintf('%04d-%02d', $r->y, $r->m);
            $map[$key] = (int) $r->total;
        }

        $labels = [];
        $data = [];
        $cursor = $start->copy();
        for ($i = 0; $i < $months; $i++) {
            $key = $cursor->format('Y-m');
            $labels[] = $cursor->format('M/Y');
            $data[] = $map[$key] ?? 0;
            $cursor->addMonth();
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function schedulesByWeekdayLast30Days(int $companyId): array
    {
        $start = Carbon::today()->subDays(29);
        $end = Carbon::today();

        // MySQL DAYOFWEEK: 1=Sunday .. 7=Saturday
        $rows = Schedule::query()
            ->selectRaw('DAYOFWEEK(schedule_date) as dow, COUNT(*) as total')
            ->whereHas('unit', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->whereBetween('schedule_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->where('status', '!=', 'cancelled')
            ->groupBy('dow')
            ->get();

        $map = [];
        foreach ($rows as $r) {
            $map[(int) $r->dow] = (int) $r->total;
        }

        // Build Monday..Sunday order using MySQL indices
        $order = [2,3,4,5,6,7,1];
        $data = array_map(fn($idx) => $map[$idx] ?? 0, $order);

        return ['data' => $data];
    }

    private function paymentsByMonth(int $companyId, int $months = 12): array
    {
        $start = Carbon::now()->startOfMonth()->subMonths($months - 1);
        $end = Carbon::now()->endOfMonth();

        $rows = Payment::query()
            ->selectRaw('YEAR(created_at) as y, MONTH(created_at) as m')
            ->selectRaw('SUM(CASE WHEN status = ? THEN amount ELSE 0 END) as received', [PaymentStatusEnum::PAID->value])
            ->selectRaw('SUM(CASE WHEN status IN (?, ?) THEN amount ELSE 0 END) as receivable', [PaymentStatusEnum::PENDING->value, PaymentStatusEnum::OVERDUE->value])
            ->where('company_id', $companyId)
            ->whereBetween('created_at', [$start->startOfDay(), $end->endOfDay()])
            ->groupBy('y', 'm')
            ->orderBy('y')
            ->orderBy('m')
            ->get();

        $map = [];
        foreach ($rows as $r) {
            $key = sprintf('%04d-%02d', $r->y, $r->m);
            $map[$key] = ['received' => (float) $r->received, 'receivable' => (float) $r->receivable];
        }

        $labels = [];
        $received = [];
        $receivable = [];
        $cursor = $start->copy();
        for ($i = 0; $i < $months; $i++) {
            $key = $cursor->format('Y-m');
            $labels[] = $cursor->format('M/Y');
            $received[] = $map[$key]['received'] ?? 0.0;
            $receivable[] = $map[$key]['receivable'] ?? 0.0;
            $cursor->addMonth();
        }

        return ['labels' => $labels, 'received' => $received, 'receivable' => $receivable];
    }
}


