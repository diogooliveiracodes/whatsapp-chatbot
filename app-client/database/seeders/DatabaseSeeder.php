<?php

namespace Database\Seeders;

use App\Models\CompanySettings;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CompanySeeder::class,
            CompanySettingsSeeder::class,
            UnitSeeder::class,
            UnitSettingsSeeder::class,
            UserRoleSeeder::class,
            UserSeeder::class,
            CustomerSeeder::class,
            ChatSessionSeeder::class,
            MessageSeeder::class,
            UnitServiceTypeSeeder::class,
            ScheduleSeeder::class,
        ]);
    }
}
