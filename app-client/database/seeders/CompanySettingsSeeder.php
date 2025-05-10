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
        CompanySettings::factory()->count(1)->create([
            'company_id' => Company::first()->id,
            'name' => Company::first()->name,
        ]);
    }
}
