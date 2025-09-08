<?php

namespace App\Http\Controllers;

use App\Exceptions\Schedule\OutsideWorkingDaysException;
use App\Exceptions\Schedule\OutsideWorkingHoursException;
use App\Exceptions\Schedule\ScheduleBlockedException;
use App\Exceptions\Schedule\ScheduleConflictException;
use App\Models\Customer;
use App\Models\Unit;
use App\Models\Schedule;
use App\Models\Company;
use App\Repositories\ScheduleRepository;
use App\Services\Schedule\ScheduleBlockService;
use App\Services\Schedule\ScheduleTimeService;
use App\Services\Schedule\SchedulePaymentService;
use App\Services\Payment\AsaasCustomerService;
use App\Services\ErrorLog\ErrorLogService;
use App\Services\Schedule\Validators\WorkingDaysValidator;
use App\Services\Schedule\Validators\WorkingHoursValidator;
use App\Enum\AsaasCustomerTypeEnum;
use App\Http\Resources\PublicScheduleResource;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\View\View;

class ScheduleLinkController extends Controller
{
    public function __construct(
        private readonly ScheduleRepository $scheduleRepository,
        private readonly ScheduleTimeService $scheduleTimeService,
        private readonly ScheduleBlockService $scheduleBlockService,
        private readonly WorkingDaysValidator $workingDaysValidator,
        private readonly WorkingHoursValidator $workingHoursValidator,
        private readonly SchedulePaymentService $schedulePaymentService,
        private readonly AsaasCustomerService $asaasCustomerService,
        private readonly ErrorLogService $errorLogService,
    ) {}

    /**
     * Entry point: list active units for a specific company or redirect if only one.
     */
    public function index($company): View|RedirectResponse
    {
        $units = Unit::query()
            ->where('active', true)
            ->where('company_id', $company)
            ->get();

        if ($units->count() === 1) {
            return redirect()->route('schedule-link.show', ['company' => $company, 'unit' => $units->first()->id]);
        }

        return view('schedule-link.index', [
            'units' => $units,
            'company' => $company,
        ]);
    }

    /**
     * Show scheduling page for a specific unit.
     */
    public function show($company, Unit $unit, Request $request): View|RedirectResponse
    {
        // Ensure the unit belongs to the specified company
        if ($unit->company_id != $company) {
            abort(404);
        }

        $unit->load(['unitSettings', 'unitServiceTypes' => function ($q) {
            $q->where('active', true);
        }, 'company']);

        $weekStart = $request->get('week_start', now()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d'));
        $weekDays = $this->getWeekDays($unit, $weekStart);

        // Check if current week has any available days
        $hasAvailableDays = collect($weekDays)->contains('available', true);

        // If no available days and this is the initial load (no week_start parameter), try next week
        if (!$hasAvailableDays && !$request->has('week_start')) {
            $nextWeekStart = Carbon::parse($weekStart)->addWeek()->format('Y-m-d');
            $nextWeekDays = $this->getWeekDays($unit, $nextWeekStart);
            $nextWeekHasAvailableDays = collect($nextWeekDays)->contains('available', true);

            if ($nextWeekHasAvailableDays) {
                // Redirect to next week if it has available days
                return redirect()->route('schedule-link.show', [
                    'company' => $company,
                    'unit' => $unit->id,
                    'week_start' => $nextWeekStart
                ]);
            }
        }

        // Check if there are multiple units for this company
        $allUnits = Unit::query()
            ->where('active', true)
            ->where('company_id', $company)
            ->get();

        $hasMultipleUnits = $allUnits->count() > 1;

        return view('schedule-link.show', [
            'unit' => $unit,
            'unitSettings' => $unit->unitSettings,
            'serviceTypes' => $unit->unitServiceTypes,
            'weekStart' => $weekStart,
            'weekDays' => $weekDays,
            'company' => $company,
            'companyName' => $unit->company->name ?? 'Agendamento',
            'hasMultipleUnits' => $hasMultipleUnits,
        ]);
    }

    /**
     * JSON: Week days for a given week start (YYYY-MM-DD).
     */
    public function weekDays($company, Unit $unit, Request $request): JsonResponse
    {
        // Ensure the unit belongs to the specified company
        if ($unit->company_id != $company) {
            abort(404);
        }

        $weekStart = $request->get('week_start', now()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d'));
        $days = $this->getWeekDays($unit, $weekStart);
        return response()->json(['days' => $days]);
    }

    /**
     * JSON: Available times for a given date (YYYY-MM-DD).
     */
    public function availableTimes($company, Unit $unit, Request $request): JsonResponse
    {
        // Ensure the unit belongs to the specified company
        if ($unit->company_id != $company) {
            abort(404);
        }

        $date = $request->string('date')->toString();
        if (!$date) {
            return response()->json(['times' => []]);
        }

        $times = $this->getAvailableTimesForDate($unit, $date)->map(fn ($t) => $t->format('H:i'))->values();
        return response()->json(['times' => $times]);
    }

    /**
     * Store a schedule created via public link.
     */
    public function store($company, Unit $unit, Request $request): RedirectResponse
    {
        // Ensure the unit belongs to the specified company
        if ($unit->company_id != $company) {
            abort(404);
        }

        $unit->load('unitSettings');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'unit_service_type_id' => ['required', 'integer', 'exists:unit_service_types,id'],
            'schedule_date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i'],
        ]);

        $unitSettings = $unit->unitSettings;
        $duration = (int) ($unitSettings->appointment_duration_minutes ?? 30);

        try {
            // Validate business rules in local timezone
            $scheduleDate = Carbon::parse($validated['schedule_date']);
            $endTimeLocal = Carbon::parse($validated['start_time'])->copy()->addMinutes($duration)->format('H:i');

            if ($this->workingDaysValidator->isOutsideWorkingDays($scheduleDate, $unitSettings)) {
                throw new OutsideWorkingDaysException();
            }

            if ($this->workingHoursValidator->isOutsideWorkingHours($scheduleDate, $validated['start_time'], $endTimeLocal, $unitSettings)) {
                throw new OutsideWorkingHoursException();
            }

            // Convert to UTC for persistence and conflict checks
            [$utcDate, $utcStart, $utcEnd] = $this->convertToUtc($validated['schedule_date'], $validated['start_time'], $duration, $unitSettings->timezone);

            // Conflict and block validations
            if ($this->scheduleRepository->findConflictingSchedule($unit->id, $utcDate, $utcStart, $utcEnd, null)) {
                throw new ScheduleConflictException();
            }
            if ($this->scheduleBlockService->isTimeSlotBlocked($unit->id, $utcDate, $utcStart, $utcEnd)) {
                throw new ScheduleBlockedException();
            }

            // Resolve or create customer
            $customer = Customer::query()
                ->where('company_id', $unit->company_id)
                ->where('phone', $validated['phone'])
                ->first();
            if (!$customer) {
                $customer = Customer::create([
                    'active' => true,
                    'company_id' => $unit->company_id,
                    'unit_id' => $unit->id,
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                ]);
            }

            // Pick a responsible user for the unit
            $targetUser = $unit->users()->where('active', true)->first() ?? $unit->users()->first();
            if (!$targetUser) {
                return back()->withErrors(['general' => Lang::get('schedule_link.messages.no_user_available')])->withInput();
            }

            $schedule = $this->scheduleRepository->create([
                'unit_id' => $unit->id,
                'customer_id' => $customer->id,
                'user_id' => $targetUser->id,
                'unit_service_type_id' => (int) $validated['unit_service_type_id'],
                'schedule_date' => $utcDate,
                'start_time' => $utcStart,
                'end_time' => $utcEnd,
                'status' => 'pending',
                'notes' => null,
                'is_confirmed' => true,
                'active' => true,
            ]);

            return redirect()
                ->route('schedule-link.success', ['company' => $company, 'unit' => $unit->id, 'uuid' => $schedule->uuid])
                ->with('status', Lang::get('schedule_link.messages.created'))
                ->with('schedule_data', (new PublicScheduleResource($schedule))->toArray(request()));
        } catch (OutsideWorkingDaysException $e) {
            return back()->withErrors(['schedule_date' => Lang::get('schedules.messages.outside_working_days')])->withInput();
        } catch (OutsideWorkingHoursException $e) {
            return back()->withErrors(['start_time' => Lang::get('schedules.messages.outside_working_hours')])->withInput();
        } catch (ScheduleConflictException $e) {
            return back()->withErrors(['start_time' => Lang::get('schedules.messages.time_conflict')])->withInput();
        } catch (ScheduleBlockedException $e) {
            return back()->withErrors(['start_time' => Lang::get('schedules.messages.time_blocked')])->withInput();
        } catch (\Throwable $e) {
            $this->errorLogService->logError(new \Exception('Aconteceu um erro ao criar o agendamento: ' . json_encode($e->getMessage())), ['action' => 'store']);

            return back()->withErrors(['general' => Lang::get('schedule_link.messages.unexpected_error')])->withInput();
        }
    }

    /**
     * Success page.
     */
    public function success($company, Unit $unit, string $uuid, Request $request): View
    {
        $schedule = $this->scheduleRepository->findByUuid($uuid);

        // Ensure the unit belongs to the specified company
        if ($unit->company_id != $company) {
            abort(404);
        }

        // Ensure the schedule belongs to the specified unit
        if ($schedule->unit_id != $unit->id) {
            abort(404);
        }

        // Get schedule data from session if available, otherwise use the schedule from route
        $scheduleData = null;
        if (session()->has('schedule_data')) {
            $scheduleData = session('schedule_data');
        } else {
            $scheduleData = (new PublicScheduleResource($schedule))->toArray(request());
        }

        // Verificar se já existe um pagamento para este agendamento
        $existingPayment = \App\Models\Payment::where('schedule_id', $schedule->id)->orderByDesc('created_at')->first();
        $paymentStatus = null;

        if ($existingPayment) {
            $paymentStatus = [
                'exists' => true,
                'status' => $existingPayment->status->value,
                'payment_id' => $existingPayment->gateway_payment_id,
                'pix_copy_paste' => $existingPayment->pix_copy_paste,
            ];
        }

        return view('schedule-link.success', [
            'unit' => $unit,
            'company' => $company,
            'schedule' => $scheduleData,
            'paymentStatus' => $paymentStatus,
        ]);
    }

    /**
     * Generate PIX payment for schedule
     */
    public function generatePayment(Company $company, Schedule $schedule, Request $request): JsonResponse
    {
        try {
            // Ensure the schedule belongs to the specified company
            if ($schedule->unit->company_id != $company->id) {
                return response()->json(['success' => false, 'error' => 'Agendamento não encontrado'], 404);
            }

            $company->load('companySettings');
            if (!$company->companySettings || !$company->companySettings->gateway_api_key) {
                return response()->json([
                    'success' => false,
                    'error' => 'Configurações de pagamento não encontradas para esta empresa'
                ], 400);
            }

            // Load the customer relationship if not already loaded
            if (!$schedule->relationLoaded('customer')) {
                $schedule->load('customer');
            }

            // Update customer document_number if provided and not already set
            $documentNumber = $request->input('document_number');
            if (!empty($documentNumber) && empty($schedule->customer->document_number)) {
                $schedule->customer->update(['document_number' => $documentNumber]);
                // Reload the customer to get the updated data
                $schedule->load('customer');
            }

            // Check if customer exists in Asaas
            $customerExists = $this->asaasCustomerService->customerExists([
                'type' => AsaasCustomerTypeEnum::CUSTOMER->value,
                'customer_id' => $schedule->customer_id,
            ]);

            if (!$customerExists) {
                $customerDocument = $schedule->customer->document_number;
                if (empty($customerDocument)) {
                    throw new \Exception('Customer document number not found');
                }

                $asaasCustomer = $this->asaasCustomerService->create([
                    'type' => AsaasCustomerTypeEnum::CUSTOMER->value,
                    'customer_id' => $schedule->customer_id,
                    'name' => $schedule->customer->name,
                    'cpf_cnpj' => $customerDocument,
                ]);

                $integrationResult = $this->asaasCustomerService->integrateCustomerToAsaas($asaasCustomer, $company->companySettings->gateway_api_key);

                if ($integrationResult !== true) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Erro ao integrar cliente com Asaas: ' . $integrationResult
                    ], 500);
                }
            }

            $asaasCustomer = $this->asaasCustomerService->findByCustomerId($schedule->customer_id);

            $this->errorLogService->logError(new \Exception('ASAAS CUSTOMER: ' . json_encode($asaasCustomer)), ['action' => 'generatePayment', 'schedule_id' => $schedule->id]);

            $response = $this->schedulePaymentService->generateSchedulePayment(
                $schedule,
                $asaasCustomer,
                $company->companySettings->gateway_api_key
            );

            return response()->json(['success' => true, 'data' => $response]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'generatePayment', 'schedule_id' => $schedule->id]);

            return response()->json(['success' => false, 'error' => 'Erro ao gerar pagamento'], 500);
        }
    }

    /**
     * Get PIX code for schedule payment
     */
    public function getPixCode(Company $company, Schedule $schedule, Request $request): JsonResponse
    {
        try {
            $request->validate([
                'payment_id' => 'required|string'
            ]);

            // Ensure the schedule belongs to the specified company
            if ($schedule->unit->company_id != $company->id) {
                return response()->json(['success' => false, 'error' => 'Agendamento não encontrado'], 404);
            }

            $company->load('companySettings');
            if (!$company->companySettings || !$company->companySettings->gateway_api_key) {
                return response()->json([
                    'success' => false,
                    'error' => 'Configurações de pagamento não encontradas para esta empresa'
                ], 400);
            }

            $response = $this->schedulePaymentService->getSchedulePixCode(
                $schedule,
                $request->payment_id,
                $company->companySettings->gateway_api_key
            );

            return response()->json(['success' => true, 'data' => $response]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'getPixCode', 'schedule_id' => $schedule->id, 'payment_id' => $request->payment_id ?? null]);

            return response()->json(['success' => false, 'error' => 'Erro ao obter código PIX'], 500);
        }
    }

    /**
     * Check payment status for schedule
     */
    public function checkPaymentStatus(Company $company, Schedule $schedule, Request $request): JsonResponse
    {
        try {
            $request->validate([
                'payment_id' => 'required|string'
            ]);

            // Ensure the schedule belongs to the specified company
            if ($schedule->unit->company_id != $company->id) {
                return response()->json(['success' => false, 'error' => 'Agendamento não encontrado'], 404);
            }

            $company->load('companySettings');
            if (!$company->companySettings || !$company->companySettings->gateway_api_key) {
                return response()->json([
                    'success' => false,
                    'error' => 'Configurações de pagamento não encontradas para esta empresa'
                ], 400);
            }

            $this->errorLogService->logError(new \Exception('CHECK PAYMENT STATUS: ' . $request->payment_id), ['action' => 'checkPaymentStatus', 'schedule_id' => $schedule->id, 'payment_id' => $request->payment_id ?? null]);

            // Verificar se já existe um pagamento ativo para este agendamento
            $existingPayment = \App\Models\Payment::where('schedule_id', $schedule->id)
                ->where('status', \App\Enum\PaymentStatusEnum::PAID)
                ->first();

            if ($existingPayment) {
                // Se já houver um pagamento PAID (status 2), não exibir o card de pagamento
                return response()->json([
                    'success' => true,
                    'data' => [
                        'status' => 'CONFIRMED',
                        'internal_status' => \App\Enum\PaymentStatusEnum::PAID->value,
                        'hide_payment_card' => true,
                        'message' => 'Pagamento já foi realizado para este agendamento'
                    ]
                ]);
            }

            // Verificar se existe pagamento PENDING
            $pendingPayment = \App\Models\Payment::where('schedule_id', $schedule->id)
                ->where('status', \App\Enum\PaymentStatusEnum::PENDING)
                ->first();

            if ($pendingPayment) {
                // Se já houver um pagamento PENDING (status 1), exibir o card com código PIX preenchido
                $response = $this->schedulePaymentService->checkSchedulePaymentStatus(
                    $schedule,
                    $request->payment_id,
                    $company->companySettings->gateway_api_key
                );

                // Adicionar informações do pagamento pendente
                $response['existing_pending_payment'] = true;
                $response['pix_copy_paste'] = $pendingPayment->pix_copy_paste;
                $response['payment_id'] = $pendingPayment->gateway_payment_id;

                return response()->json(['success' => true, 'data' => $response]);
            }

            // Verificar se existe pagamento REJECTED, EXPIRED ou OVERDUE
            $failedPayment = \App\Models\Payment::where('schedule_id', $schedule->id)
                ->whereIn('status', [
                    \App\Enum\PaymentStatusEnum::REJECTED,
                    \App\Enum\PaymentStatusEnum::EXPIRED,
                    \App\Enum\PaymentStatusEnum::OVERDUE
                ])
                ->first();

            if ($failedPayment) {
                // Se já houver um pagamento com status de falha, exibir o card com botão para gerar novo código PIX
                $response = $this->schedulePaymentService->checkSchedulePaymentStatus(
                    $schedule,
                    $request->payment_id,
                    $company->companySettings->gateway_api_key
                );

                // Adicionar informações do pagamento com falha
                $response['existing_failed_payment'] = true;
                $response['failed_payment_status'] = $failedPayment->status->value;
                $response['show_new_pix_button'] = true;

                return response()->json(['success' => true, 'data' => $response]);
            }

            // Se não há pagamento existente, verificar status normalmente
            $response = $this->schedulePaymentService->checkSchedulePaymentStatus(
                $schedule,
                $request->payment_id,
                $company->companySettings->gateway_api_key
            );

            return response()->json(['success' => true, 'data' => $response]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, ['action' => 'checkPaymentStatus', 'schedule_id' => $schedule->id, 'payment_id' => $request->payment_id ?? null]);

            return response()->json(['success' => false, 'error' => 'Erro ao verificar status do pagamento'], 500);
        }
    }

    /**
     * Helpers
     */
    private function getWeekDays(Unit $unit, string $weekStart): array
    {
        $unit->loadMissing('unitSettings');
        $unitSettings = $unit->unitSettings;
        $duration = (int) ($unitSettings->appointment_duration_minutes ?? 30);

        $tz = $unitSettings->timezone ?? 'America/Sao_Paulo';

        // Anchor week range in unit timezone to avoid UTC vs local mismatches
        $start = Carbon::parse($weekStart . ' 00:00', $tz)->startOfWeek(Carbon::SUNDAY);
        $end = $start->copy()->endOfWeek(Carbon::SATURDAY);

        $todayLocal = now($tz)->startOfDay();

        $weekDays = [];
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $dateStr = $cursor->format('Y-m-d');
            // Compare in the same timezone (cursor is in $tz)
            $isFutureOrToday = $cursor->greaterThanOrEqualTo($todayLocal);
            $isWorkingDay = !$this->workingDaysValidator->isOutsideWorkingDays($cursor, $unitSettings);

            if ($isFutureOrToday && $isWorkingDay) {
                // Check if there are available slots for this day
                $hasAnySlot = $this->getAvailableTimesForDate($unit, $dateStr)->isNotEmpty();
                $weekDays[] = [
                    'date' => $dateStr,
                    'available' => $hasAnySlot,
                    'day_of_week' => $cursor->dayOfWeek,
                    'day' => $cursor->day,
                    'month' => $cursor->format('M'),
                    'is_today' => $cursor->isSameDay($todayLocal)
                ];
            } else {
                // Past days or non-working days are marked as unavailable
                $weekDays[] = [
                    'date' => $dateStr,
                    'available' => false,
                    'day_of_week' => $cursor->dayOfWeek,
                    'day' => $cursor->day,
                    'month' => $cursor->format('M'),
                    'is_today' => $cursor->isSameDay($todayLocal)
                ];
            }

            $cursor->addDay();
        }

        return $weekDays;
    }

    private function getAvailableTimesForDate(Unit $unit, string $date): \Illuminate\Support\Collection
    {
        $unit->loadMissing('unitSettings');
        $unitSettings = $unit->unitSettings;
        $duration = (int) ($unitSettings->appointment_duration_minutes ?? 30);

        $dayOfWeek = Carbon::parse($date)->dayOfWeek + 1;
        $map = [1 => 'sunday', 2 => 'monday', 3 => 'tuesday', 4 => 'wednesday', 5 => 'thursday', 6 => 'friday', 7 => 'saturday'];
        $dayKey = $map[$dayOfWeek];

        // Generate time slots based on unit settings and local timezone
        $slots = $this->scheduleTimeService->getAvailableTimeSlots(Carbon::parse($date), $unitSettings)
            ->filter(function (Carbon $time) use ($dayKey, $unitSettings) {
                return $this->scheduleTimeService->isWithinOperatingHours($time, $dayKey, $unitSettings);
            });

        // Filter out conflicts and blocks
        $available = $slots->filter(function (Carbon $localStart) use ($unit, $unitSettings, $duration, $date, $dayKey) {
            // Skip past times for today
            $nowLocal = now($unitSettings->timezone ?? 'America/Sao_Paulo');
            if ($date === $nowLocal->format('Y-m-d') && $localStart->lte($nowLocal)) {
                return false;
            }

            // Check if time slot is inside break period
            if ($this->scheduleTimeService->isInsideBreakPeriod($localStart, $dayKey, $unitSettings, $duration)) {
                return false;
            }

            [$utcDate, $utcStart, $utcEnd] = $this->convertToUtc($date, $localStart->format('H:i'), $duration, $unitSettings->timezone);

            if ($this->scheduleRepository->findConflictingSchedule($unit->id, $utcDate, $utcStart, $utcEnd, null)) {
                return false;
            }
            if ($this->scheduleBlockService->isTimeSlotBlocked($unit->id, $utcDate, $utcStart, $utcEnd)) {
                return false;
            }
            return true;
        });

        return $available->values();
    }

    /**
     * Convert local date/time to UTC strings used for persistence.
     * Returns array [utcDate:Y-m-d, utcStart:H:i, utcEnd:H:i]
     */
    private function convertToUtc(string $localDate, string $localStartTime, int $durationMinutes, ?string $timezone): array
    {
        $tz = $timezone ?: 'America/Sao_Paulo';
        $startLocal = Carbon::parse($localDate . ' ' . $localStartTime, $tz);
        $startUtc = $startLocal->copy()->setTimezone('UTC');
        $endUtc = $startLocal->copy()->addMinutes($durationMinutes)->setTimezone('UTC');

        return [
            $startUtc->format('Y-m-d'),
            $startUtc->format('H:i'),
            $endUtc->format('H:i'),
        ];
    }
}


