<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = \App\Models\Unit::all();
        foreach ($units as $unit) {
            \App\Models\UnitSettings::factory()->create([
                'company_id' => $unit->company_id,
                'unit_id' => $unit->id,
                'name' => $unit->name,
                'default_language' => 'pt-BR',
                'timezone' => 'America/Sao_Paulo',
                'sunday' => false,
                'monday' => true,
                'tuesday' => true,
                'wednesday' => true,
                'thursday' => true,
                'friday' => true,
                'saturday' => false,
            ]);
        }
    }
}
