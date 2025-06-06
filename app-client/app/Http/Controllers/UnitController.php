<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Services\Unit\UnitService;
use App\Services\ErrorLog\ErrorLogService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UnitController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param UnitService $unitService Service for handling unit operations
     * @param ErrorLogService $errorLogService Service for handling error logging
     */
    public function __construct(protected UnitService $unitService, protected ErrorLogService $errorLogService) {}

    /**
     * Display a listing of the units.
     *
     * @return \Illuminate\View\View
     * @throws \Exception When there's an error fetching the units
     */
    public function index(): View
    {
        try {
            $units = $this->unitService->getUnits()->load('UnitSettingsId');
            return view('units.index', compact('units'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'index',
                'request_method' => request()->method(),
                'request_url' => request()->url(),
            ]);
            return view('units.index', ['units' => [], 'error' => __('units.error.load')]);
        }
    }

    /**
     * Show the form for creating a new unit.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Exception When there's an error loading the create form
     */
    public function create(): View|RedirectResponse
    {
        try {
            return view('units.create');
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'create',
                'request_method' => request()->method(),
                'request_url' => request()->url(),
            ]);
            return redirect()->route('units.index')->with('error', __('units.error.create_form'));
        }
    }

    /**
     * Store a newly created unit in storage.
     *
     * @param StoreUnitRequest $request The validated request containing unit data
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception When there's an error creating the unit
     */
    public function store(StoreUnitRequest $request): RedirectResponse
    {
        try {
            $unit = $this->unitService->create($request->validated());
            return redirect()->route('units.show', $unit)->with('success', __('units.success.created'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'store',
                'data' => $request->validated(),
                'request_method' => $request->method(),
                'request_url' => $request->url(),
            ]);
            return redirect()->back()->withInput()->with('error', __('units.error.create'));
        }
    }

    /**
     * Display the specified unit.
     *
     * @param Unit $unit The unit to display
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Exception When there's an error displaying the unit
     */
    public function show(Unit $unit): View|RedirectResponse
    {
        try {
            $unit = $unit->load('UnitSettingsId');
            return view('units.show', compact('unit'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'show',
                'unit_id' => $unit->id,
                'request_method' => request()->method(),
                'request_url' => request()->url(),
            ]);
            return redirect()->route('units.index')->with('error', __('units.error.show'));
        }
    }

    /**
     * Show the form for editing the specified unit.
     *
     * @param Unit $unit The unit to edit
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Exception When there's an error loading the edit form
     */
    public function edit(Unit $unit): View|RedirectResponse
    {
        try {
            $unit = $unit->load('UnitSettingsId');
            return view('units.edit', compact('unit'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'edit',
                'unit_id' => $unit->id,
                'request_method' => request()->method(),
                'request_url' => request()->url(),
            ]);
            return redirect()->route('units.index')->with('error', __('units.error.edit_form'));
        }
    }

    /**
     * Update the specified unit in storage.
     *
     * @param UpdateUnitRequest $request The validated request containing updated unit data
     * @param Unit $unit The unit to update
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception When there's an error updating the unit
     */
    public function update(UpdateUnitRequest $request, Unit $unit): RedirectResponse
    {
        try {
            $this->unitService->update($unit, $request->validated());
            return redirect()->route('units.show', $unit)->with('success', __('units.success.updated'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'update',
                'unit_id' => $unit->id,
                'data' => $request->validated(),
                'request_method' => $request->method(),
                'request_url' => $request->url(),
            ]);
            return redirect()->back()->withInput()->with('error', __('units.error.update'));
        }
    }

    /**
     * Deactivate the specified unit.
     *
     * @param Unit $unit The unit to deactivate
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception When there's an error deleting the unit
     */
    public function deactivate(Unit $unit): RedirectResponse
    {
        try {
            $this->unitService->deactivate($unit);
            return redirect()->route('units.index')->with('success', __('units.success.deactivated'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'destroy',
                'unit_id' => $unit->id,
                'request_method' => request()->method(),
                'request_url' => request()->url(),
            ]);
            return redirect()->back()->with('error', __('units.error.deactivated'));
        }
    }
}
