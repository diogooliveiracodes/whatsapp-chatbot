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
                'sunday_start' => null,
                'sunday_end' => null,
                'sunday' => false,
                'monday_start' => '08:00:00',
                'monday_end' => '18:00:00',
                'monday' => true,
                'tuesday_start' => '08:00:00',
                'tuesday_end' => '18:00:00',
                'tuesday' => true,
                'wednesday_start' => '08:00:00',
                'wednesday_end' => '18:00:00',
                'wednesday' => true,
                'thursday_start' => '08:00:00',
                'thursday_end' => '18:00:00',
                'thursday' => true,
                'friday_start' => '08:00:00',
                'friday_end' => '18:00:00',
                'friday' => true,
                'saturday_start' => null,
                'saturday_end' => null,
                'saturday' => false,
            ]);
        }
    }
}
