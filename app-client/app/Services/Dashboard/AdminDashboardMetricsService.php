<?php

namespace App\Services\Dashboard;

use App\Enum\PaymentStatusEnum;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Signature;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardMetricsService
{
    private array $cache = [];

    public function getMetricsForAdmin(string $period = '1_month'): array
    {
        if (isset($this->cache[$period])) {
            return $this->cache[$period];
        }

        $dateRange = $this->getDateRange($period);

        $result = [
            'period' => $period,
            'date_range' => $dateRange,
            'kpis' => [
                'companies' => $this->getCompanyMetrics($dateRange),
                'users' => $this->getUserMetrics($dateRange),
                'schedules' => $this->getScheduleMetrics($dateRange),
                'payments' => $this->getPaymentMetrics($dateRange),
                'subscriptions' => $this->getSubscriptionMetrics($dateRange),
            ],
        ];

        // Cache for this request lifecycle
        $this->cache[$period] = $result;
        return $result;
    }

    private function getDateRange(string $period): array
    {
        $now = Carbon::now();

        return match ($period) {
            '6_months' => [
                'start' => $now->copy()->subMonths(6)->startOfDay(),
                'end' => $now->endOfDay(),
            ],
            '1_year' => [
                'start' => $now->copy()->subYear()->startOfDay(),
                'end' => $now->endOfDay(),
            ],
            'all_time' => [
                'start' => null,
                'end' => $now->endOfDay(),
            ],
            default => [ // 1_month
                'start' => $now->copy()->subMonth()->startOfDay(),
                'end' => $now->endOfDay(),
            ],
        };
    }

    private function getCompanyMetrics(array $dateRange): array
    {
        $query = Company::query();

        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        $total = Company::count();
        $active = Company::where('active', true)->count();
        $inactive = Company::where('active', false)->count();
        $newInPeriod = $query->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'new_in_period' => $newInPeriod,
        ];
    }

    private function getUserMetrics(array $dateRange): array
    {
        $query = User::query();

        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        $total = User::count();
        $active = User::where('active', true)->count();
        $inactive = User::where('active', false)->count();
        $newInPeriod = $query->count();

        // Users by role
        $admins = User::where('user_role_id', 1)->count();
        $owners = User::where('user_role_id', 2)->count();
        $employees = User::where('user_role_id', 3)->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'new_in_period' => $newInPeriod,
            'by_role' => [
                'admins' => $admins,
                'owners' => $owners,
                'employees' => $employees,
            ],
        ];
    }

    private function getScheduleMetrics(array $dateRange): array
    {
        $query = Schedule::query();

        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        $total = Schedule::count();
        $active = Schedule::where('active', true)->count();
        $inactive = Schedule::where('active', false)->count();
        $newInPeriod = $query->count();

        // Schedules by status
        $pending = Schedule::where('schedules.status', 'pending')->count();
        $confirmed = Schedule::where('schedules.status', 'confirmed')->count();
        $cancelled = Schedule::where('schedules.status', 'cancelled')->count();
        $completed = Schedule::where('schedules.status', 'completed')->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'new_in_period' => $newInPeriod,
            'by_status' => [
                'pending' => $pending,
                'confirmed' => $confirmed,
                'cancelled' => $cancelled,
                'completed' => $completed,
            ],
        ];
    }

    private function getPaymentMetrics(array $dateRange): array
    {
        $query = Payment::query();

        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        $total = Payment::count();
        $newInPeriod = $query->count();

        // Payments by status
        $pending = Payment::where('payments.status', PaymentStatusEnum::PENDING->value)->count();
        $paid = Payment::where('payments.status', PaymentStatusEnum::PAID->value)->count();
        $rejected = Payment::where('payments.status', PaymentStatusEnum::REJECTED->value)->count();
        $expired = Payment::where('payments.status', PaymentStatusEnum::EXPIRED->value)->count();
        $overdue = Payment::where('payments.status', PaymentStatusEnum::OVERDUE->value)->count();

        // Payment amounts
        $totalAmount = Payment::sum('amount');
        $paidAmount = Payment::where('payments.status', PaymentStatusEnum::PAID->value)->sum('amount');
        $pendingAmount = Payment::where('payments.status', PaymentStatusEnum::PENDING->value)->sum('amount');
        $overdueAmount = Payment::where('payments.status', PaymentStatusEnum::OVERDUE->value)->sum('amount');

        return [
            'total' => $total,
            'new_in_period' => $newInPeriod,
            'by_status' => [
                'pending' => $pending,
                'paid' => $paid,
                'rejected' => $rejected,
                'expired' => $expired,
                'overdue' => $overdue,
            ],
            'amounts' => [
                'total' => (float) $totalAmount,
                'paid' => (float) $paidAmount,
                'pending' => (float) $pendingAmount,
                'overdue' => (float) $overdueAmount,
            ],
        ];
    }

    private function getSubscriptionMetrics(array $dateRange): array
    {
        $query = Signature::query();

        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        $total = Signature::count();
        $newInPeriod = $query->count();

        // Subscriptions by status
        $active = Signature::where('signatures.status', 'active')->count();
        $inactive = Signature::where('signatures.status', 'inactive')->count();
        $cancelled = Signature::where('signatures.status', 'cancelled')->count();

        // Revenue from subscriptions
        $totalRevenue = Signature::where('signatures.status', 'active')
            ->join('plans', 'signatures.plan_id', '=', 'plans.id')
            ->sum('plans.price');

        return [
            'total' => $total,
            'new_in_period' => $newInPeriod,
            'by_status' => [
                'active' => $active,
                'inactive' => $inactive,
                'cancelled' => $cancelled,
            ],
            'revenue' => (float) $totalRevenue,
        ];
    }
}
