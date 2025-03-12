<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    const USER_ROLE_ADMIN_ID = 1;
    const USER_ROLE_OWNER_ID = 2;
    const USER_ROLE_EMPLOYEE_ID = 3;
    const FIRST_COMPANY_ID = 1;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(
            CompanySeeder::class
        );

        $this->call(
            UserRoleSeeder::class
        );

        User::factory()->create([
            'email' => 'admin@email.com',
            'company_id' => self::FIRST_COMPANY_ID,
            'user_role_id' => self::USER_ROLE_ADMIN_ID,
            'password' => Hash::make('123456789')
        ]);

    }
}
