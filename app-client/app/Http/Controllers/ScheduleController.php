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
use App\Enum\DaysOfWeekEnum;

/**
 * Controller responsible for managing schedules in the application.
 * Handles CRUD operations for schedules including validation of working hours and days.
 */
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
     * Retrieves schedules for the current user's unit along with related data.
     *
     * @param Request $request The incoming request containing user information
     * @return View The view containing the list of schedules and related data
     * @throws \Exception When there's an error loading the schedules
     */
    public function index(Request $request): View
    {
        try {
            $unit = Auth::user()->unit;
            $schedules = $this->scheduleService->getSchedulesByUnitAndDate($unit->id, $request->date);
            $customers = $this->customerService->getCustomersByUnit($unit);
            $unit->load('unitSettings');
            $workingHours = $this->scheduleService->getWorkingHours($unit->unitSettings);
            $availableTimeSlots = $this->scheduleService->getAvailableTimeSlots($date ?? now(), $unit->unitSettings);
            $days = DaysOfWeekEnum::getDaysOfWeek();
            $startOfWeek = $this->scheduleService->getStartAndEndDate($request->date)[0];

            return view('schedules.index', [
                'schedules' => ScheduleResource::collection($schedules),
                'customers' => $customers,
                'unit' => $unit,
                'unitSettings' => $unit->unitSettings,
                'workingHours' => $workingHours,
                'availableTimeSlots' => $availableTimeSlots,
                'scheduleService' => $this->scheduleService,
                'startOfWeek' => $startOfWeek,
                'days' => $days,
            ]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);

            return view('schedules.index')->with('error', __('schedules.messages.load_error'));
        }
    }

    /**
     * Show the form for creating a new schedule.
     * Retrieves necessary data for the schedule creation form.
     *
     * @return View The view containing the schedule creation form with required data
     * @throws \Exception When there's an error loading the form data
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
     * Validates and creates a new schedule with the provided data.
     *
     * @param StoreScheduleRequest $request The validated request containing schedule data
     * @return RedirectResponse Redirects to the schedules index with success/error message
     * @throws OutsideWorkingDaysException When schedule is outside working days
     * @throws OutsideWorkingHoursException When schedule is outside working hours
     * @throws ScheduleConflictException When there's a conflict with existing schedules
     * @throws \Exception When there's an unexpected error during creation
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
     * Show the form for editing a schedule.
     * Retrieves necessary data for the schedule editing form.
     *
     * @param Schedule $schedule The schedule model instance to be edited
     * @return View The view containing the schedule editing form with required data
     * @throws \Exception When there's an error loading the form data
     */
    public function edit(Schedule $schedule): View
    {
        try {
            $unitServiceTypes = $this->unitServiceTypeService->getUnitServiceTypesByUnit(Auth::user()->unit);
            $customers = $this->customerService->getCustomersByUnit();

            return view('schedules.edit', [
                'schedule' => $schedule,
                'unitServiceTypes' => $unitServiceTypes,
                'customers' => $customers,
            ]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);

            return view('schedules.index')->with('error', __('schedules.messages.load_error'));
        }
    }

    /**
     * Update the specified schedule.
     * Validates and updates an existing schedule with new data.
     *
     * @param UpdateScheduleRequest $request The validated request containing updated schedule data
     * @param Schedule $schedule The schedule model instance to be updated
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure
     * @throws OutsideWorkingDaysException When schedule is outside working days
     * @throws OutsideWorkingHoursException When schedule is outside working hours
     * @throws ScheduleConflictException When there's a conflict with existing schedules
     * @throws \Exception When there's an unexpected error during update
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule): RedirectResponse
    {
        try {
            $this->scheduleService->handleScheduleUpdate($request->validated(), $schedule);

            return redirect()->route('schedules.index')->with('success', __('schedules.messages.updated'));
        } catch (OutsideWorkingDaysException $e) {
            return redirect()->route('schedules.edit', $schedule->id)->withInput()->with('error', __('schedules.messages.outside_working_days'));
        } catch (OutsideWorkingHoursException $e) {
            return redirect()->route('schedules.edit', $schedule->id)->withInput()->with('error', __('schedules.messages.outside_working_hours'));
        } catch (ScheduleConflictException $e) {
            return redirect()->route('schedules.edit', $schedule->id)->withInput()->with('error', __('schedules.messages.time_conflict'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);

            return redirect()->route('schedules.edit', $schedule->id)->withInput()->with('error', __('schedules.messages.edit_error'));
        }
    }

    /**
     * Remove the specified schedule.
     * Deletes a schedule from the system.
     *
     * @param Schedule $schedule The schedule model instance to be deleted
     * @return RedirectResponse Redirects to the schedules index with success/error message
     * @throws \Exception When there's an error during deletion
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
