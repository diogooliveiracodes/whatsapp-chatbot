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
    const FIRST_COMPANY_ID = 1;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'admin@email.com',
            'company_id' => self::FIRST_COMPANY_ID,
            'user_role_id' => self::USER_ROLE_ADMIN_ID,
            'password' => Hash::make('123456789')
        ]);

        User::factory()->create([
            'email' => 'owner@email.com',
            'company_id' => self::FIRST_COMPANY_ID,
            'user_role_id' => self::USER_ROLE_OWNER_ID,
            'password' => Hash::make('123456789')
        ]);

        User::factory()->create([
            'email' => 'employee1@email.com',
            'company_id' => self::FIRST_COMPANY_ID,
            'user_role_id' => self::USER_ROLE_EMPLOYEE_ID,
            'password' => Hash::make('123456789')
        ]);

        User::factory()->create([
            'email' => 'employee2@email.com',
            'company_id' => self::FIRST_COMPANY_ID,
            'user_role_id' => self::USER_ROLE_EMPLOYEE_ID,
            'password' => Hash::make('123456789')
        ]);

        User::factory()->create([
            'email' => 'employee3@email.com',
            'company_id' => self::FIRST_COMPANY_ID,
            'user_role_id' => self::USER_ROLE_EMPLOYEE_ID,
            'password' => Hash::make('123456789')
        ]);
    }
}
