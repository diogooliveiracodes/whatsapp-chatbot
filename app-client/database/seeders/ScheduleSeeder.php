<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Unit;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        // Criar uma unidade de teste
        $unit = Unit::factory()->create();

        // Criar alguns usuários para a unidade
        $users = User::factory()->count(3)->create([
            'unit_id' => $unit->id,
            'user_role_id' => 3 // ID do role 'employee'
        ]);

        // Criar alguns clientes para a unidade
        $customers = Customer::factory()->count(10)->create([
            'unit_id' => $unit->id
        ]);

        // Criar agendamentos para os próximos 30 dias
        foreach ($users as $user) {
            // Criar 5-10 agendamentos por usuário
            Schedule::factory()
                ->count(rand(5, 10))
                ->create([
                    'unit_id' => $unit->id,
                    'user_id' => $user->id,
                    'customer_id' => fn() => $customers->random()->id
                ]);
        }
    }
}
