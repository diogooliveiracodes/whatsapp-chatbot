<?php

namespace App\Http\Controllers;

use App\Models\UnitSettings;
use App\Http\Requests\UpdateUnitSettingsRequest;
use App\Services\UnitSettings\UnitSettingsService;
use App\Services\ErrorLog\ErrorLogService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UnitSettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param UnitSettingsService $unitSettingsService Service for handling unit settings operations
     * @param ErrorLogService $errorLogService Service for handling error logging
     */
    public function __construct(
        protected UnitSettingsService $unitSettingsService,
        protected ErrorLogService $errorLogService
    ) {}

    /**
     * Display the specified unit settings.
     *
     * @param UnitSettings $unitSettings The unit settings to display
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Exception When there's an error loading the unit settings
     */
    public function show(UnitSettings $unitSettings): View|RedirectResponse
    {
        try {
            $unitSettings = $unitSettings->load('Unit');
            return view('unit_settings.show', compact('unitSettings'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'show',
                'unit_settings_id' => $unitSettings->id,
                'request_method' => request()->method(),
                'request_url' => request()->url(),
            ]);
            return redirect()->route('unitSettings.index')->with('error', __('unitSettings.error.show'));
        }
    }

    /**
     * Show the form for editing the specified unit settings.
     *
     * @param UnitSettings $unitSettings The unit settings to edit
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Exception When there's an error loading the edit form
     */
    public function edit(UnitSettings $unitSettings): View|RedirectResponse
    {
        try {
            return view('unit_settings.edit', compact('unitSettings'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'edit',
                'unit_settings_id' => $unitSettings->id,
                'request_method' => request()->method(),
                'request_url' => request()->url(),
            ]);
            return redirect()->route('unitSettings.index')->with('error', __('unitSettings.error.edit_form'));
        }
    }

    /**
     * Update the specified unit settings in storage.
     *
     * @param UpdateUnitSettingsRequest $request The validated request containing updated unit settings data
     * @param UnitSettings $unitSettings The unit settings to update
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception When there's an error updating the unit settings
     */
    public function update(UpdateUnitSettingsRequest $request, UnitSettings $unitSettings): RedirectResponse
    {
        try {
            $this->unitSettingsService->update($unitSettings, $request->validated());
            return redirect()->route('unitSettings.show', $unitSettings)
                ->with('success', __('unitSettings.success.updated'));
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'action' => 'update',
                'unit_settings_id' => $unitSettings->id,
                'request_method' => request()->method(),
                'request_url' => request()->url(),
                'request_data' => $request->validated(),
            ]);
            return redirect()->back()->with('error', __('unitSettings.error.update'));
        }
    }
}
