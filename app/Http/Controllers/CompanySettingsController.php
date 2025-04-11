<?php

namespace App\Http\Controllers;

use App\Models\CompanySettings;
use App\Http\Requests\UpdateCompanySettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanySettingsController extends Controller
{

    public function __construct()
    {
    }

    /**
     * @param CompanySettings $companySettings
     * @return View
     */
    public function show(CompanySettings $companySettings): View
    {
        return view('company_settings.show', compact('companySettings'));
    }

    /**
     * @param CompanySettings $companySettings
     * @return View
     */
    public function edit(CompanySettings $companySettings): View
    {
        return view('company_settings.edit', compact('companySettings'));
    }

    /**
     * @param UpdateCompanySettingsRequest $request
     * @param CompanySettings $companySettings
     * @return RedirectResponse
     */

    public function update(UpdateCompanySettingsRequest $request, CompanySettings $companySettings): RedirectResponse
    {
        try {
            $companySettings->update($request->validated());
            return redirect()
                ->route('company-settings.show', $companySettings)
                ->with('success', __('company-settings.update-success'));

        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withErrors(['update_error' => 'Could not update settings. Try again later.']);
        }
    }
}
