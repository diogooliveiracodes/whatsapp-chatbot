<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Unit;
use App\Models\Customer;
use App\Services\ErrorLog\ErrorLogService;
use App\Services\Customer\CustomerService;
use App\Services\Http\HttpResponseService;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Exceptions\Schedule\ScheduleException;
use App\Exceptions\Schedule\OutsideWorkingDaysException;
use App\Exceptions\Schedule\OutsideWorkingHoursException;
use App\Exceptions\Schedule\ScheduleConflictException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\Schedule\ScheduleService;

class ScheduleController extends Controller
{
    public function __construct(
        protected ErrorLogService $errorLogService,
        protected ScheduleService $scheduleService,
        protected CustomerService $customerService,
        protected HttpResponseService $httpResponse
    ) {}

    public function index(Request $request)
    {
        $unit = $request->user()->unit;
        $schedules = $this->scheduleService->getSchedulesByUnit($unit->id);
        $customers = $this->customerService->getCustomersByUnit($unit);
        $unit->load('unitSettings');

        return view('schedules.index', [
            'schedules' => ScheduleResource::collection($schedules),
            'customers' => $customers,
            'unit' => $unit,
        ]);
    }

    public function create(Request $request)
    {
        $customers = $this->customerService->getCustomersByUnit();

        return view('schedules.create', [
            'customers' => $customers
        ]);
    }

    public function store(StoreScheduleRequest $request)
    {
        try {
            $validated = $request->validated();
            $unit = $request->user()->unit;
            $unitSettings = $unit->unitSettings;

            $scheduleDate = Carbon::parse($validated['schedule_date']);
            if ($this->scheduleService->isOutsideWorkingDays($scheduleDate, $unitSettings)) {
                throw new OutsideWorkingDaysException();
            }

            if ($this->scheduleService->isOutsideWorkingHours($validated['start_time'], $validated['end_time'], $unitSettings)) {
                throw new OutsideWorkingHoursException();
            }

            if ($this->scheduleService->hasConflict($unit->id, $validated['schedule_date'], $validated['start_time'], $validated['end_time'], null)) {
                throw new ScheduleConflictException();
            }

            $scheduleData = array_merge($validated, [
                'unit_id' => $unit->id,
                'user_id' => $request->user()->id,
                'status' => 'pending',
                'is_confirmed' => false,
            ]);

            $this->scheduleService->createSchedule($scheduleData);

            return redirect()->route('schedules.index')->with('success', __('schedules.messages.created'));
        } catch (ScheduleException $e) {
            return $this->httpResponse->error(__('schedules.messages.create_error'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);
            return $this->httpResponse->error(
                __('schedules.messages.create_error')
            );
        }
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        try {
            $validated = $request->validated();
            $unit = $request->user()->unit;
            $unitSettings = $unit->unitSettings;

            $scheduleDate = Carbon::parse($validated['schedule_date']);
            if ($this->scheduleService->isOutsideWorkingDays($scheduleDate, $unitSettings)) {
                throw new OutsideWorkingDaysException();
            }

            if ($this->scheduleService->isOutsideWorkingHours($validated['start_time'], $validated['end_time'], $unitSettings)) {
                throw new OutsideWorkingHoursException();
            }

            if ($this->scheduleService->hasConflict($unit->id, $validated['schedule_date'], $validated['start_time'], $validated['end_time'], $schedule->id)) {
                throw new ScheduleConflictException();
            }

            $this->scheduleService->updateSchedule($schedule, $validated);

            return $this->httpResponse->success(__('schedules.messages.updated'));

        } catch (\Exception|ScheduleException  $e) {
            $this->errorLogService->logError($e);
            return $this->httpResponse->error(
                __('schedules.messages.update_error', ['message' => $e->getMessage()])
            );
        }
    }

    public function destroy(Schedule $schedule)
    {
        try {
            $this->scheduleService->deleteSchedule($schedule);
            return $this->httpResponse->success(__('schedules.messages.deleted'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);
            return $this->httpResponse->error(
                __('schedules.messages.delete_error')
            );
        }
    }

    public function cancel(Schedule $schedule)
    {
        try {
            $this->scheduleService->cancelSchedule($schedule);
            return $this->httpResponse->success(__('schedules.messages.cancelled'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);
            return $this->httpResponse->error(
                __('schedules.messages.cancel_error')
            );
        }
    }
}
