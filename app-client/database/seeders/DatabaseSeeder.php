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
        $this->call(
            CompanySeeder::class
        );

        $this->call(
          CompanySettingsSeeder::class
        );

        $this->call(
            UnitSeeder::class
        );

        $this->call(
            UserRoleSeeder::class
        );

        $this->call(
            UserSeeder::class
        );

        $this->call(
            CustomerSeeder::class
        );

        $this->call(
            ChatSessionSeeder::class
        );

        $this->call(
            MessageSeeder::class
        );

        $this->call([
            ScheduleSeeder::class,
        ]);
    }
}
