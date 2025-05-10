<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    const USER_ROLE_ADMIN_ID = 1;
    const USER_ROLE_OWNER_ID = 2;
    const USER_ROLE_EMPLOYEE_ID = 3;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'admin@email.com',
            'user_role_id' => self::USER_ROLE_ADMIN_ID,
        ]);

        User::factory()->create([
            'email' => 'owner@email.com',
            'user_role_id' => self::USER_ROLE_OWNER_ID,
        ]);

        $employeeList = DataMocks::getEmployees();

        foreach ($employeeList as $employee) {
            User::factory()->create([
               'email' => $employee['email'],
               'unit_id' => $employee['unit_id'],
               'user_role_id' => self::USER_ROLE_EMPLOYEE_ID,
            ]);
        }
    }
}
