<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Enum\PlansEnum;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'id' => 1,
                'name' => 'TRIAL',
                'description' => 'Plano de teste gratuito para conhecer a plataforma',
                'price' => 0.00,
                'duration_months' => 1,
                'units_limit' => 1,
                'status' => 'active',
                'type' => PlansEnum::TRIAL->value,
            ],
            [
                'id' => 2,
                'name' => 'BASIC',
                'description' => 'Perfeito para começar',
                'price' => 50.00,
                'duration_months' => 1,
                'units_limit' => 1,
                'status' => 'active',
                'type' => PlansEnum::BASIC->value,
            ],
            [
                'id' => 3,
                'name' => 'PRO',
                'description' => 'Receba pagamentos automaticamente',
                'price' => 100.00,
                'duration_months' => 1,
                'units_limit' => 1,
                'status' => 'active',
                'type' => PlansEnum::PRO->value,
            ],
            [
                'id' => 4,
                'name' => 'ENTERPRISE',
                'description' => 'Para empresas que possuem diversas unidades',
                'price' => 200.00,
                'duration_months' => 1,
                'units_limit' => 10,
                'status' => 'active',
                'type' => PlansEnum::ENTERPRISE->value,
            ]
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
