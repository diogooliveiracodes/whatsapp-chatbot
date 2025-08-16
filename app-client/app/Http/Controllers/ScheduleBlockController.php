<?php

namespace App\Http\Controllers;

use App\Models\ScheduleBlock;
use App\Services\ErrorLog\ErrorLogService;
use App\Services\Http\HttpResponseService;
use App\Services\Schedule\ScheduleBlockService;
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
        protected HttpResponseService $httpResponse
    ) {}

    /**
     * Display a listing of schedule blocks
     */
    public function index(): View
    {
        try {
            $unit = Auth::user()->unit;
            $blocks = $this->scheduleBlockService->getActiveBlocksByUnit($unit->id);

            return view('schedule-blocks.index', [
                'blocks' => ScheduleBlockResource::collection($blocks),
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

        return view('schedule-blocks.create', [
            'blockTypes' => $blockTypes,
            'preSelectedDate' => $request->get('block_date'),
            'preSelectedStartTime' => $request->get('start_time'),
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

        return view('schedule-blocks.edit', [
            'scheduleBlock' => $scheduleBlock,
            'blockTypes' => $blockTypes,
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
