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
use App\Services\Unit\UnitService;
use Illuminate\Support\Facades\Auth;
use App\Enum\DaysOfWeekEnum;
use App\Enum\UserRoleEnum;
use App\Exceptions\Schedule\InsideBreakPeriodException;

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
        protected UnitServiceTypeService $unitServiceTypeService,
        protected UnitService $unitService
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
            $user = Auth::user();
            $units = collect();
            $selectedUnit = null;
            $showUnitSelector = false;

            // Se o usuário é proprietário, buscar todas as unidades ativas da empresa
            if ($user->isOwner()) {
                $units = $this->unitService->getUnits();

                // Se há mais de uma unidade, mostrar o seletor
                if ($units->count() > 1) {
                    $showUnitSelector = true;

                    // Obter a unidade selecionada (da query string ou padrão da unidade do usuário)
                    $selectedUnitId = $request->get('unit_id', $user->unit_id);
                    $selectedUnit = $units->firstWhere('id', $selectedUnitId) ?? $user->unit;
                } else {
                    // Se há apenas uma unidade, selecioná-la automaticamente
                    $selectedUnit = $units->first();
                }
            } else {
                // Para outros tipos de usuário, usar a unidade padrão
                $selectedUnit = $user->unit;
            }

            $selectedUnit->load('unitSettings');

            $schedules = $this->scheduleService->getSchedulesByUnitAndDate($selectedUnit->id, $request->date, $selectedUnit->unitSettings);
            $startAndEndDate = $this->scheduleService->getStartAndEndDate($request->date, $selectedUnit->unitSettings);
            $blocks = $this->scheduleBlockService->getBlocksByUnitAndDate($selectedUnit->id, $startAndEndDate[0], $startAndEndDate[1]);
            $customers = $this->customerService->getCustomersByCompany($selectedUnit);
            $workingHours = $this->scheduleService->getWorkingHours($selectedUnit->unitSettings);
            $availableTimeSlots = $this->scheduleService->getAvailableTimeSlots($date ?? now(), $selectedUnit->unitSettings);
            $days = DaysOfWeekEnum::getDaysOfWeek();
            $startOfWeek = $startAndEndDate[0];

            return view('schedules.weekly', [
                'schedules' => ScheduleResource::collection($schedules),
                'blocks' => ScheduleBlockResource::collection($blocks),
                'customers' => $customers,
                'unit' => $selectedUnit,
                'unitSettings' => $selectedUnit->unitSettings,
                'workingHours' => $workingHours,
                'availableTimeSlots' => $availableTimeSlots,
                'scheduleService' => $this->scheduleService,
                'scheduleBlockService' => $this->scheduleBlockService,
                'startOfWeek' => $startOfWeek,
                'days' => $days,
                'units' => $units,
                'showUnitSelector' => $showUnitSelector,
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
            $user = Auth::user();
            $units = collect();
            $selectedUnit = null;
            $showUnitSelector = false;

            // Se o usuário é proprietário, buscar todas as unidades ativas da empresa
            if ($user->isOwner()) {
                $units = $this->unitService->getUnits();

                // Se há mais de uma unidade, mostrar o seletor
                if ($units->count() > 1) {
                    $showUnitSelector = true;

                    // Obter a unidade selecionada (da query string ou padrão da unidade do usuário)
                    $selectedUnitId = $request->get('unit_id', $user->unit_id);
                    $selectedUnit = $units->firstWhere('id', $selectedUnitId) ?? $user->unit;
                } else {
                    // Se há apenas uma unidade, selecioná-la automaticamente
                    $selectedUnit = $units->first();
                }
            } else {
                // Para outros tipos de usuário, usar a unidade padrão
                $selectedUnit = $user->unit;
            }

            $selectedUnit->load('unitSettings');
            $date = $request->get('date', now()->format('Y-m-d'));

            $schedules = $this->scheduleService->getSchedulesByUnitAndDay($selectedUnit->id, $date, $selectedUnit->unitSettings);
            $blocks = $this->scheduleBlockService->getBlocksByUnitAndDate($selectedUnit->id, Carbon::parse($date), Carbon::parse($date));
            $customers = $this->customerService->getCustomersByCompany($selectedUnit);
            $workingHours = $this->scheduleService->getWorkingHours($selectedUnit->unitSettings);
            $availableTimeSlots = $this->scheduleService->getAvailableTimeSlots(Carbon::parse($date), $selectedUnit->unitSettings);

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
                'unit' => $selectedUnit,
                'unitSettings' => $selectedUnit->unitSettings,
                'workingHours' => $workingHours,
                'availableTimeSlots' => $availableTimeSlots,
                'scheduleService' => $this->scheduleService,
                'scheduleBlockService' => $this->scheduleBlockService,
                'date' => Carbon::parse($date),
                'dayKey' => $dayKey,
                'dayName' => $dayName,
                'units' => $units,
                'showUnitSelector' => $showUnitSelector,
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
            $user = Auth::user();
            $units = collect();
            $selectedUnit = $user->unit;
            $showUnitSelector = false;

            // Se o usuário é proprietário, buscar todas as unidades ativas da empresa
            if ($user->isOwner()) {
                $units = $this->unitService->getUnits();

                // Se há mais de uma unidade, mostrar o seletor
                if ($units->count() > 1) {
                    $showUnitSelector = true;

                    // Obter a unidade selecionada (da query string ou padrão da unidade do usuário)
                    $selectedUnitId = request()->get('unit_id', $user->unit_id);
                    $selectedUnit = $units->firstWhere('id', $selectedUnitId) ?? $user->unit;
                } else {
                    // Se há apenas uma unidade, selecioná-la automaticamente
                    $selectedUnit = $units->first();
                }
            }

            $unitServiceTypes = $this->unitServiceTypeService->getUnitServiceTypesByUnit($selectedUnit)->where('active', true);
            $customers = $this->customerService->getCustomersByCompany();

            return view('schedules.create', [
                'customers' => $customers,
                'unitServiceTypes' => $unitServiceTypes,
                'units' => $units,
                'showUnitSelector' => $showUnitSelector,
                'selectedUnit' => $selectedUnit,
            ]);
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
            $user = Auth::user();
            $selectedUnit = $user->unit;

            // Se o usuário é proprietário, determinar a unidade selecionada
            if ($user->isOwner()) {
                $units = $this->unitService->getUnits();

                if ($units->count() > 1) {
                    // Obter a unidade selecionada (do formulário, query string ou padrão da unidade do usuário)
                    $selectedUnitId = $request->get('unit_id') ?? request()->get('unit_id', $user->unit_id);
                    $selectedUnit = $units->firstWhere('id', $selectedUnitId) ?? $user->unit;
                } else {
                    // Se há apenas uma unidade, selecioná-la automaticamente
                    $selectedUnit = $units->first();
                }
            }

            $result = $this->scheduleService->handleScheduleCreation($request->validated(), $selectedUnit);

            return view('schedules.created-success', ['schedule' => (new ScheduleResource($result))->toArray(request())]);
        } catch (OutsideWorkingDaysException $e) {

            return redirect()
                ->route('schedules.create', request()->has('unit_id') ? ['unit_id' => request('unit_id')] : [])
                ->withInput()
                ->with('error', __('schedules.messages.outside_working_days'));
        } catch (OutsideWorkingHoursException $e) {

            return redirect()
                ->route('schedules.create', request()->has('unit_id') ? ['unit_id' => request('unit_id')] : [])
                ->withInput()
                ->with('error', __('schedules.messages.outside_working_hours'));
        } catch (ScheduleConflictException $e) {

            return redirect()
                ->route('schedules.create', request()->has('unit_id') ? ['unit_id' => request('unit_id')] : [])
                ->withInput()
                ->with('error', __('schedules.messages.time_conflict'));
        } catch (ScheduleBlockedException $e) {

            return redirect()
                ->route('schedules.create', request()->has('unit_id') ? ['unit_id' => request('unit_id')] : [])
                ->withInput()
                ->with('error', __('schedules.messages.time_blocked'));
        } catch (InsideBreakPeriodException $e) {

            return redirect()
                ->route('schedules.create', request()->has('unit_id') ? ['unit_id' => request('unit_id')] : [])
                ->withInput()
                ->with('error', __('schedules.messages.inside_break_period'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);

            return redirect()
                ->route('schedules.create', request()->has('unit_id') ? ['unit_id' => request('unit_id')] : [])
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
            $user = Auth::user();
            $units = collect();
            $selectedUnit = $user->unit;
            $showUnitSelector = false;

            // Se o usuário é proprietário, buscar todas as unidades ativas da empresa
            if ($user->isOwner()) {
                $units = $this->unitService->getUnits();

                // Se há mais de uma unidade, mostrar o seletor
                if ($units->count() > 1) {
                    $showUnitSelector = true;

                    // Obter a unidade selecionada (da query string ou padrão da unidade do usuário)
                    $selectedUnitId = request()->get('unit_id', $user->unit_id);
                    $selectedUnit = $units->firstWhere('id', $selectedUnitId) ?? $user->unit;
                } else {
                    // Se há apenas uma unidade, selecioná-la automaticamente
                    $selectedUnit = $units->first();
                }
            }

            $unitServiceTypes = $this->unitServiceTypeService->getUnitServiceTypesByUnit($selectedUnit)->where('active', true);
            $customers = $this->customerService->getCustomersByCompany();

            return view('schedules.edit', [
                'schedule' => $schedule,
                'unitServiceTypes' => $unitServiceTypes,
                'customers' => $customers,
                'units' => $units,
                'showUnitSelector' => $showUnitSelector,
                'selectedUnit' => $selectedUnit,
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
     * @return \Illuminate\Contracts\View\View|RedirectResponse Returns success view or redirects with error message
     * @throws OutsideWorkingDaysException When schedule is outside working days
     * @throws OutsideWorkingHoursException When schedule is outside working hours
     * @throws ScheduleConflictException When there's a conflict with existing schedules
     * @throws \Exception When there's an unexpected error during update
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule): \Illuminate\Contracts\View\View|RedirectResponse
    {
        try {
            $user = Auth::user();
            $selectedUnit = $schedule->unit; // Por padrão, usar a unidade do agendamento

            // Se o usuário é proprietário, determinar a unidade selecionada
            if ($user->isOwner()) {
                $units = $this->unitService->getUnits();

                if ($units->count() > 1) {
                    // Obter a unidade selecionada (do formulário, query string ou padrão da unidade do usuário)
                    $selectedUnitId = $request->get('unit_id') ?? request()->get('unit_id', $user->unit_id);
                    $selectedUnit = $units->firstWhere('id', $selectedUnitId) ?? $user->unit;
                } else {
                    // Se há apenas uma unidade, selecioná-la automaticamente
                    $selectedUnit = $units->first();
                }
            }

            $result = $this->scheduleService->validateAndUpdateSchedule($request->validated(), $schedule, $selectedUnit);

            return view('schedules.updated-success', ['schedule' => (new ScheduleResource($result))->toArray(request())]);
        } catch (PastScheduleException $e) {
            return redirect()->route('schedules.edit', array_merge([$schedule->id], request()->has('unit_id') ? ['unit_id' => request('unit_id')] : []))->withInput()->with('error', __('schedules.messages.past_schedule'));
        } catch (OutsideWorkingDaysException $e) {
            return redirect()->route('schedules.edit', array_merge([$schedule->id], request()->has('unit_id') ? ['unit_id' => request('unit_id')] : []))->withInput()->with('error', __('schedules.messages.outside_working_days'));
        } catch (OutsideWorkingHoursException $e) {
            return redirect()->route('schedules.edit', array_merge([$schedule->id], request()->has('unit_id') ? ['unit_id' => request('unit_id')] : []))->withInput()->with('error', __('schedules.messages.outside_working_hours'));
        } catch (ScheduleConflictException $e) {
            return redirect()->route('schedules.edit', array_merge([$schedule->id], request()->has('unit_id') ? ['unit_id' => request('unit_id')] : []))->withInput()->with('error', __('schedules.messages.time_conflict'));
        } catch (ScheduleBlockedException $e) {
            return redirect()->route('schedules.edit', array_merge([$schedule->id], request()->has('unit_id') ? ['unit_id' => request('unit_id')] : []))->withInput()->with('error', __('schedules.messages.time_blocked'));
        } catch (InsideBreakPeriodException $e) {
            return redirect()->route('schedules.edit', array_merge([$schedule->id], request()->has('unit_id') ? ['unit_id' => request('unit_id')] : []))->withInput()->with('error', __('schedules.messages.inside_break_period'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);

            return redirect()->route('schedules.edit', array_merge([$schedule->id], request()->has('unit_id') ? ['unit_id' => request('unit_id')] : []))->withInput()->with('error', __('schedules.messages.edit_error'));
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
