<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PlansSeeder::class,
            // CompanySeeder::class,
            // CompanySettingsSeeder::class,
            // UnitSeeder::class,
            // UnitSettingsSeeder::class,
            // UserRoleSeeder::class,
            // UserSeeder::class,
            // CustomerSeeder::class,
            // ChatSessionSeeder::class,
            // MessageSeeder::class,
            // UnitServiceTypeSeeder::class,
            // ScheduleSeeder::class,
        ]);

        $this->seedOnlyAdmin();
    }

    public function seedOnlyAdmin()
    {
        Company::factory()->create([
            'name' => 'Admin Company',
            'active' => true,
        ]);

        Unit::factory()->create([
            'company_id' => 1,
            'name' => 'Admin Unit',
            'active' => true,
        ]);
        UserRole::factory()->create([
            'name' => 'admin',
            'active' => true,
            'company_id' => 1,
        ]);
        UserRole::factory()->create([
            'name' => 'owner',
            'active' => true,
            'company_id' => 1,
        ]);
        UserRole::factory()->create([
            'name' => 'employee',
            'active' => true,
            'company_id' => 1,
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'user_role_id' => 1,
            'company_id' => 1,
            'unit_id' => 1,
        ]);
    }
}
