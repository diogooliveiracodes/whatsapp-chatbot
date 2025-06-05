<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\UnitServiceType;
use Illuminate\Database\Seeder;

class UnitServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = Unit::all();

        foreach ($units as $unit) {
            // Create 5-8 service types for each unit
            UnitServiceType::factory()
                ->count(rand(5, 8))
                ->create([
                    'company_id' => $unit->company_id,
                    'unit_id' => $unit->id,
                ]);
        }
    }
}
