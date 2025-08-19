<?php

namespace App\Http\Controllers;

use App\Models\ScheduleBlock;
use App\Services\ErrorLog\ErrorLogService;
use App\Services\Http\HttpResponseService;
use App\Services\Schedule\ScheduleBlockService;
use App\Services\Unit\UnitService;
use App\Http\Requests\StoreScheduleBlockRequest;
use App\Http\Requests\UpdateScheduleBlockRequest;
use App\Http\Resources\ScheduleBlockResource;
use App\Enum\ScheduleBlockTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ScheduleBlockController extends Controller
{
    public function __construct(
        protected ErrorLogService $errorLogService,
        protected ScheduleBlockService $scheduleBlockService,
        protected HttpResponseService $httpResponse,
        protected UnitService $unitService
    ) {}

    /**
     * Display a listing of schedule blocks
     */
    public function index(): View
    {
        try {
            $user = Auth::user();
            $units = collect();
            $selectedUnit = null;
            $showUnitSelector = false;

            if ($user->isOwner()) {
                $units = $this->unitService->getUnits();

                if ($units->count() > 1) {
                    $showUnitSelector = true;
                    $selectedUnitId = request()->get('unit_id', $user->unit_id);
                    $selectedUnit = $units->firstWhere('id', (int) $selectedUnitId) ?? $user->unit;
                } else {
                    $selectedUnit = $units->first();
                }
            } else {
                $selectedUnit = $user->unit;
            }

            $blocks = $this->scheduleBlockService->getActiveBlocksByUnit($selectedUnit->id);

            return view('schedule-blocks.index', [
                'blocks' => ScheduleBlockResource::collection($blocks),
                'units' => $units,
                'unit' => $selectedUnit,
                'showUnitSelector' => $showUnitSelector,
            ]);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);
            return view('schedule-blocks.index')->with('error', __('schedule-blocks.messages.load_error') . ': ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new schedule block
     */
    public function create(Request $request): View
    {
        $blockTypes = ScheduleBlockTypeEnum::cases();

        // Unidade selecionada para criação
        $user = Auth::user();
        $units = collect();
        $selectedUnit = $user->unit;
        $showUnitSelector = false;

        if ($user->isOwner()) {
            $units = $this->unitService->getUnits();
            if ($units->count() > 1) {
                $showUnitSelector = true;
                $selectedUnitId = $request->get('unit_id', $user->unit_id);
                $selectedUnit = $units->firstWhere('id', (int) $selectedUnitId) ?? $user->unit;
            } else {
                $selectedUnit = $units->first();
            }
        }

        return view('schedule-blocks.create', [
            'blockTypes' => $blockTypes,
            'preSelectedDate' => $request->get('block_date'),
            'preSelectedStartTime' => $request->get('start_time'),
            'units' => $units,
            'selectedUnit' => $selectedUnit,
            'showUnitSelector' => $showUnitSelector,
        ]);
    }

    /**
     * Store a newly created schedule block
     */
    public function store(StoreScheduleBlockRequest $request): RedirectResponse
    {
        try {
            $this->scheduleBlockService->validateAndCreateBlock($request->validated());

            return redirect()
                ->route('schedule-blocks.index')
                ->with('success', __('schedule-blocks.messages.created'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);

            return redirect()
                ->route('schedule-blocks.create')
                ->withInput()
                ->with('error', __('schedule-blocks.messages.create_error', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Show the form for editing a schedule block
     */
    public function edit(ScheduleBlock $scheduleBlock): View
    {
        $blockTypes = ScheduleBlockTypeEnum::cases();

        $user = Auth::user();
        $units = collect();
        $selectedUnit = $scheduleBlock->unit ?? $user->unit;
        $showUnitSelector = false;

        if ($user->isOwner()) {
            $units = $this->unitService->getUnits();
            if ($units->count() > 1) {
                $showUnitSelector = true;
                $selectedUnitId = request()->get('unit_id', $selectedUnit->id);
                $selectedUnit = $units->firstWhere('id', (int) $selectedUnitId) ?? $selectedUnit;
            } else {
                $selectedUnit = $units->first();
            }
        }

        return view('schedule-blocks.edit', [
            'scheduleBlock' => $scheduleBlock,
            'blockTypes' => $blockTypes,
            'units' => $units,
            'selectedUnit' => $selectedUnit,
            'showUnitSelector' => $showUnitSelector,
        ]);
    }

    /**
     * Update the specified schedule block
     */
    public function update(UpdateScheduleBlockRequest $request, ScheduleBlock $scheduleBlock): RedirectResponse
    {
        try {
            $this->scheduleBlockService->validateAndUpdateBlock($scheduleBlock, $request->validated());

            return redirect()
                ->route('schedule-blocks.index')
                ->with('success', __('schedule-blocks.messages.updated'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);

            return redirect()
                ->route('schedule-blocks.edit', $scheduleBlock->id)
                ->withInput()
                ->with('error', __('schedule-blocks.messages.update_error', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Remove the specified schedule block
     */
    public function destroy(ScheduleBlock $scheduleBlock): RedirectResponse
    {
        try {
            $this->scheduleBlockService->deleteBlock($scheduleBlock);

            return redirect()
                ->route('schedule-blocks.index')
                ->with('success', __('schedule-blocks.messages.deleted'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e);

            return redirect()
                ->route('schedule-blocks.index')
                ->with('error', __('schedule-blocks.messages.delete_error', ['message' => $e->getMessage()]));
        }
    }
}
