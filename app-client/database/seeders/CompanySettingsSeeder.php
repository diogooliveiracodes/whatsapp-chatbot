<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanySettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = \App\Models\Company::all();
        foreach ($companies as $company) {
            \App\Models\CompanySettings::factory()->create([
                'company_id' => $company->id,
            ]);
        }
    }
}
