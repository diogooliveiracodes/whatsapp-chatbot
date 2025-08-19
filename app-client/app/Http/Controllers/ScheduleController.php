<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Services\ErrorLog\ErrorLogService;
use App\Services\Customer\CustomerService;
use App\Services\Http\HttpResponseService;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\ScheduleBlockResource;
use App\Exceptions\Schedule\ScheduleException;
use App\Exceptions\Schedule\OutsideWorkingDaysException;
use App\Exceptions\Schedule\OutsideWorkingHoursException;
use App\Exceptions\Schedule\ScheduleConflictException;
use App\Exceptions\Schedule\ScheduleBlockedException;
use App\Exceptions\Schedule\PastScheduleException;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Services\Schedule\ScheduleService;
use App\Services\Schedule\ScheduleBlockService;
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
        protected ScheduleBlockService $scheduleBlockService,
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
    public function weekly(Request $request): View
    {
        try {
            $unit = Auth::user()->unit;
            $unit->load('unitSettings');

            $schedules = $this->scheduleService->getSchedulesByUnitAndDate($unit->id, $request->date, $unit->unitSettings);
            $startAndEndDate = $this->scheduleService->getStartAndEndDate($request->date, $unit->unitSettings);
            $blocks = $this->scheduleBlockService->getBlocksByUnitAndDate($unit->id, $startAndEndDate[0], $startAndEndDate[1]);
            $customers = $this->customerService->getCustomersByUnit($unit);
            $workingHours = $this->scheduleService->getWorkingHours($unit->unitSettings);
            $availableTimeSlots = $this->scheduleService->getAvailableTimeSlots($date ?? now(), $unit->unitSettings);
            $days = DaysOfWeekEnum::getDaysOfWeek();
            $startOfWeek = $startAndEndDate[0];

            return view('schedules.weekly', [
                'schedules' => ScheduleResource::collection($schedules),
                'blocks' => ScheduleBlockResource::collection($blocks),
                'customers' => $customers,
                'unit' => $unit,
                'unitSettings' => $unit->unitSettings,
                'workingHours' => $workingHours,
                'availableTimeSlots' => $availableTimeSlots,
                'scheduleService' => $this->scheduleService,
                'scheduleBlockService' => $this->scheduleBlockService,
                'startOfWeek' => $startOfWeek,
                'days' => $days,
            ]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);

            return view('schedules.weekly')->with('error', __('schedules.messages.load_error'));
        }
    }

    /**
     * Display the daily schedule view.
     * Retrieves schedules for a specific day with mobile-first responsive design.
     *
     * @param Request $request The incoming request containing date parameter
     * @return View The view containing the daily schedule
     * @throws \Exception When there's an error loading the schedules
     */
    public function daily(Request $request): View
    {
        try {
            $unit = Auth::user()->unit;
            $unit->load('unitSettings');
            $date = $request->get('date', now()->format('Y-m-d'));

            $schedules = $this->scheduleService->getSchedulesByUnitAndDay($unit->id, $date, $unit->unitSettings);
            $blocks = $this->scheduleBlockService->getBlocksByUnitAndDate($unit->id, Carbon::parse($date), Carbon::parse($date));
            $customers = $this->customerService->getCustomersByUnit($unit);
            $workingHours = $this->scheduleService->getWorkingHours($unit->unitSettings);
            $availableTimeSlots = $this->scheduleService->getAvailableTimeSlots(Carbon::parse($date), $unit->unitSettings);

            // Map day names correctly
            $dayMapping = [
                'Sunday' => 'sunday',
                'Monday' => 'monday',
                'Tuesday' => 'tuesday',
                'Wednesday' => 'wednesday',
                'Thursday' => 'thursday',
                'Friday' => 'friday',
                'Saturday' => 'saturday'
            ];

            $dayOfWeek = Carbon::parse($date)->format('l');
            $dayKey = $dayMapping[$dayOfWeek] ?? 'monday';
            $days = DaysOfWeekEnum::getDaysOfWeek();
            $dayName = $days[$dayKey] ?? $dayOfWeek;

            return view('schedules.daily', [
                'schedules' => ScheduleResource::collection($schedules),
                'blocks' => ScheduleBlockResource::collection($blocks),
                'customers' => $customers,
                'unit' => $unit,
                'unitSettings' => $unit->unitSettings,
                'workingHours' => $workingHours,
                'availableTimeSlots' => $availableTimeSlots,
                'scheduleService' => $this->scheduleService,
                'scheduleBlockService' => $this->scheduleBlockService,
                'date' => Carbon::parse($date),
                'dayKey' => $dayKey,
                'dayName' => $dayName,
            ]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);

            return view('schedules.daily')->with('error', __('schedules.messages.load_error'));
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
            $unitServiceTypes = $this->unitServiceTypeService->getUnitServiceTypesByUnit(Auth::user()->unit)->where('active', true);
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
     * @return \Illuminate\Contracts\View\View|RedirectResponse Returns success view or redirects with error message
     * @throws OutsideWorkingDaysException When schedule is outside working days
     * @throws OutsideWorkingHoursException When schedule is outside working hours
     * @throws ScheduleConflictException When there's a conflict with existing schedules
     * @throws \Exception When there's an unexpected error during creation
     */
    public function store(StoreScheduleRequest $request): \Illuminate\Contracts\View\View|RedirectResponse
    {
        try {
            $result = $this->scheduleService->handleScheduleCreation($request->validated());

            return view('schedules.created-success', ['schedule' => (new ScheduleResource($result))->toArray(request())]);
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
        } catch (ScheduleBlockedException $e) {

            return redirect()
                ->route('schedules.create')
                ->withInput()
                ->with('error', __('schedules.messages.time_blocked'));
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
            $unitServiceTypes = $this->unitServiceTypeService->getUnitServiceTypesByUnit(Auth::user()->unit)->where('active', true);
            $customers = $this->customerService->getCustomersByUnit();

            return view('schedules.edit', [
                'schedule' => $schedule,
                'unitServiceTypes' => $unitServiceTypes,
                'customers' => $customers,
            ]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);

            return view('schedules.weekly')->with('error', __('schedules.messages.load_error'));
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
            $this->scheduleService->validateAndUpdateSchedule($request->validated(), $schedule);

            return redirect()->route('schedules.weekly')->with('success', __('schedules.messages.updated'));
        } catch (PastScheduleException $e) {
            return redirect()->route('schedules.edit', $schedule->id)->withInput()->with('error', __('schedules.messages.past_schedule'));
        } catch (OutsideWorkingDaysException $e) {
            return redirect()->route('schedules.edit', $schedule->id)->withInput()->with('error', __('schedules.messages.outside_working_days'));
        } catch (OutsideWorkingHoursException $e) {
            return redirect()->route('schedules.edit', $schedule->id)->withInput()->with('error', __('schedules.messages.outside_working_hours'));
        } catch (ScheduleConflictException $e) {
            return redirect()->route('schedules.edit', $schedule->id)->withInput()->with('error', __('schedules.messages.time_conflict'));
        } catch (ScheduleBlockedException $e) {
            return redirect()->route('schedules.edit', $schedule->id)->withInput()->with('error', __('schedules.messages.time_blocked'));
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

            return redirect()->back()->with('success', __('schedules.messages.deleted'));
        } catch (PastScheduleException $e) {
            return redirect()->back()->with('error', __('schedules.messages.past_schedule'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);

            return redirect()->back()->with('error', __('schedules.messages.delete_error'));
        }
    }
}
