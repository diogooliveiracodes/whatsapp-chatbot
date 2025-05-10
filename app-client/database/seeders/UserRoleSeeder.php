<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserRole::factory()->create([
            'name' => 'admin',
            'active' => true
        ]);
        UserRole::factory()->create([
            'name' => 'owner',
            'active' => true
        ]);
        UserRole::factory()->create([
            'name' => 'employee',
            'active' => true
        ]);
    }
}
