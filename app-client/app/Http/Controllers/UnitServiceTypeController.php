<?php

namespace App\Http\Controllers;

use App\Models\UnitServiceType;
use App\Services\UnitServiceType\UnitServiceTypeService;
use App\Services\ErrorLog\ErrorLogService;
use App\Http\Requests\StoreUnitServiceTypeRequest;
use App\Http\Requests\UpdateUnitServiceTypeRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\Unit\UnitService;

class UnitServiceTypeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param UnitServiceTypeService $unitServiceTypeService Service for handling unit service type operations
     * @param ErrorLogService $errorLogService Service for handling error logging
     * @param UnitService $unitService Service for handling unit operations
     */
    public function __construct(
        protected UnitServiceTypeService $unitServiceTypeService,
        protected ErrorLogService $errorLogService,
        protected UnitService $unitService
    ) {}

    /**
     * Display a listing of the unit service types.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        try {
            $unitServiceTypes = $this->unitServiceTypeService->getUnitServiceTypes();
            return view('unit-service-types.index', compact('unitServiceTypes'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'index',
                'request_method' => request()->method(),
                'request_url' => request()->url(),
            ]);
            return view('unit-service-types.index', ['unitServiceTypes' => [], 'error' => __('unit-service-types.error.load')]);
        }
    }

    /**
     * Show the form for creating a new unit service type.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $units = $this->unitService->getUnits();
        return view('unit-service-types.create', compact('units'));
    }

    /**
     * Display the specified unit service type.
     *
     * @param UnitServiceType $unitServiceType
     * @return \Illuminate\View\View
     */
    public function show(UnitServiceType $unitServiceType): View
    {
        return view('unit-service-types.show', compact('unitServiceType'));
    }

    /**
     * Show the form for editing the specified unit service type.
     *
     * @param UnitServiceType $unitServiceType
     * @return \Illuminate\View\View
     */
    public function edit(UnitServiceType $unitServiceType): View
    {
        return view('unit-service-types.edit', compact('unitServiceType'));
    }

    /**
     * Store a newly created unit service type in storage.
     *
     * @param StoreUnitServiceTypeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUnitServiceTypeRequest $request): RedirectResponse
    {
        try {
            $this->unitServiceTypeService->create($request->validated());
            return redirect()->route('unitServiceTypes.index')
                ->with('success', __('unit-service-types.success.created'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'store',
                'request_data' => $request->all(),
            ]);
            return back()->with('error', __('unit-service-types.error.create'));
        }
    }

    /**
     * Update the specified unit service type in storage.
     *
     * @param UpdateUnitServiceTypeRequest $request
     * @param UnitServiceType $unitServiceType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUnitServiceTypeRequest $request, UnitServiceType $unitServiceType): RedirectResponse
    {
        try {
            $this->unitServiceTypeService->update($unitServiceType, $request->validated());
            return redirect()->route('unitServiceTypes.index')
                ->with('success', __('unit-service-types.success.updated'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'update',
                'unit_service_type_id' => $unitServiceType->id,
                'request_data' => $request->all(),
            ]);
            return back()->with('error', __('unit-service-types.error.update'));
        }
    }

    /**
     * Remove the specified unit service type from storage.
     *
     * @param UnitServiceType $unitServiceType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(UnitServiceType $unitServiceType): RedirectResponse
    {
        try {
            $this->unitServiceTypeService->delete($unitServiceType);
            return redirect()->route('unitServiceTypes.index')
                ->with('success', __('unit-service-types.success.deleted'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'delete',
                'unit_service_type_id' => $unitServiceType->id,
            ]);
            return back()->with('error', __('unit-service-types.error.delete'));
        }
    }
}
