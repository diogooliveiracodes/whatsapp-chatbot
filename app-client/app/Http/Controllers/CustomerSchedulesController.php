<?php

namespace App\Http\Controllers;

use App\Repositories\ScheduleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerSchedulesController extends Controller
{
    public function __construct(
        private readonly ScheduleRepository $scheduleRepository,
    ) {}

    /**
     * Public page: list schedules for a given customer UUID.
     */
    public function index(string $uuid, Request $request): View
    {
        $perPage = (int) ($request->integer('per_page') ?: 10);
        $perPage = max(5, min($perPage, 50));

        $schedules = $this->scheduleRepository->paginateByCustomerUuid($uuid, $perPage);

        return view('customer-schedules.index', [
            'customerUuid' => $uuid,
            'schedules' => $schedules,
        ]);
    }
}
