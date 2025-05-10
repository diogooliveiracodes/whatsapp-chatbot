<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $employeeList = DataMocks::getEmployees();

        foreach ($employeeList as $employee) {
            Customer::factory()->count(rand(50, 100))->create([
                'user_id' => $employee['id'],
                'unit_id' => $employee['unit_id'],
            ]);
        }
    }
}
