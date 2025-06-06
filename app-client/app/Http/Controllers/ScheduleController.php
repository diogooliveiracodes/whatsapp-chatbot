<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
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
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Services\Schedule\ScheduleService;
use App\Services\UnitServiceType\UnitServiceTypeService;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param ErrorLogService $errorLogService Service for logging errors
     * @param ScheduleService $scheduleService Service for managing schedules
     * @param CustomerService $customerService Service for managing customers
     * @param HttpResponseService $httpResponse Service for handling HTTP responses
     * @param UnitServiceTypeService $unitServiceTypeService Service for managing unit service types
     */
    public function __construct(
        protected ErrorLogService $errorLogService,
        protected ScheduleService $scheduleService,
        protected CustomerService $customerService,
        protected HttpResponseService $httpResponse,
        protected UnitServiceTypeService $unitServiceTypeService
        ) {}

    /**
     * Display a listing of schedules.
     *
     * @param Request $request The incoming request
     * @return View The view containing the list of schedules
     */
    public function index(Request $request): View
    {
        try {
            $unit = $request->user()->unit;
            $schedules = $this->scheduleService->getSchedulesByUnit($unit->id);
            $customers = $this->customerService->getCustomersByUnit($unit);
            $unit->load('unitSettings');

            $workingHours = $this->scheduleService->getWorkingHours($unit->unitSettings);
            $availableTimeSlots = $this->scheduleService->getAvailableTimeSlots(now(), $unit->unitSettings);

            return view('schedules.index', [
                'schedules' => ScheduleResource::collection($schedules),
                'customers' => $customers,
                'unit' => $unit,
                'unitSettings' => $unit->unitSettings,
                'workingHours' => $workingHours,
                'availableTimeSlots' => $availableTimeSlots,
                'scheduleService' => $this->scheduleService,
            ]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);
            return view('schedules.index')->with('error', __('schedules.messages.load_error'));
        }
    }

    /**
     * Show the form for creating a new schedule.
     *
     * @return View The view containing the schedule creation form
     */
    public function create(): View
    {
        try {
            $unitServiceTypes = $this->unitServiceTypeService->getUnitServiceTypesByUnit(Auth::user()->unit);
            $customers = $this->customerService->getCustomersByUnit();

            return view('schedules.create', ['customers' => $customers, 'unitServiceTypes' => $unitServiceTypes]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);
            return view('schedules.create')->with('error', __('schedules.messages.load_error'));
        }
    }

    /**
     * Store a newly created schedule.
     *
     * @param StoreScheduleRequest $request The validated request containing schedule data
     * @return RedirectResponse Redirects to the schedules index with success/error message
     * @throws OutsideWorkingDaysException When schedule is outside working days
     * @throws OutsideWorkingHoursException When schedule is outside working hours
     * @throws ScheduleConflictException When there's a conflict with existing schedules
     */
    public function store(StoreScheduleRequest $request): RedirectResponse
    {
        try {
            $result = $this->scheduleService->handleScheduleCreation($request->validated());

            return redirect()
                ->route('schedules.index')
                ->with('success', __('schedules.messages.created'));
        } catch (OutsideWorkingDaysException $e) {
            return redirect()
                ->route('schedules.create')
                ->withInput()
                ->with('error', __('schedules.messages.outside_working_days'));
        } catch (OutsideWorkingHoursException $e) {
            return redirect()
                ->route('schedules.create')
                ->withInput()
                ->with('error', __('schedules.messages.outside_working_hours'));
        } catch (ScheduleConflictException $e) {
            return redirect()
                ->route('schedules.create')
                ->withInput()
                ->with('error', __('schedules.messages.time_conflict'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);
            return redirect()
                ->route('schedules.create')
                ->withInput()
                ->with('error', __('schedules.messages.create_error'));
        }
    }

    /**
     * Update the specified schedule.
     *
     * @param UpdateScheduleRequest $request The validated request containing updated schedule data
     * @param Schedule $schedule The schedule to be updated
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure
     * @throws OutsideWorkingDaysException When schedule is outside working days
     * @throws OutsideWorkingHoursException When schedule is outside working hours
     * @throws ScheduleConflictException When there's a conflict with existing schedules
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule): \Illuminate\Http\JsonResponse
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
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);
            return $this->httpResponse->error(__('schedules.messages.update_error'));
        }
    }

    /**
     * Remove the specified schedule.
     *
     * @param Schedule $schedule The schedule to be deleted
     * @return RedirectResponse Redirects to the schedules index with success/error message
     */
    public function destroy(Schedule $schedule): RedirectResponse
    {
        try {

            $this->scheduleService->deleteSchedule($schedule);

            return redirect()->route('schedules.index')->with('success', __('schedules.messages.deleted'));
        } catch (\Exception $e) {

            $this->errorLogService->logError($e);

            return redirect()->route('schedules.index')->with('error', __('schedules.messages.delete_error'));
        }
    }

}
