<?php

namespace App\Http\Controllers;

use App\Models\CompanySettings;
use App\Http\Requests\StoreCompanySettingsRequest;
use App\Http\Requests\UpdateCompanySettingsRequest;

class CompanySettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanySettingsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanySettings $companySettings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanySettings $companySettings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanySettingsRequest $request, CompanySettings $companySettings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanySettings $companySettings)
    {
        //
    }
}
