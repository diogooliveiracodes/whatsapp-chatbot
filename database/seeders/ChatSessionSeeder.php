<?php

namespace Database\Seeders;

use App\Models\ChatSession;
use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customersList = Customer::all();
        foreach ($customersList as $customer) {
            ChatSession::factory()->count(rand(3, 8))->create([
                'company_id' => $customer->company_id,
                'unit_id' => $customer->unit_id,
                'customer_id' => $customer->id,
                'user_id' => $customer->user_id,
            ]);
        }
    }
}
