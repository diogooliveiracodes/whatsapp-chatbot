<?php

namespace Database\Seeders;

use App\Models\ChatSession;
use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chatSessionList = ChatSession::all();
        foreach ($chatSessionList as $chatSession) {
            Message::factory()->count(2)->create([
                'company_id' => $chatSession->company_id,
                'unit_id' => $chatSession->unit_id,
                'customer_id' => $chatSession->customer_id,
                'user_id' => null,
            ]);
            Message::factory()->count(1)->create([
                'company_id' => $chatSession->company_id,
                'unit_id' => $chatSession->unit_id,
                'customer_id' => null,
                'user_id' => $chatSession->user_id,
            ]);
            Message::factory()->count(1)->create([
                'company_id' => $chatSession->company_id,
                'unit_id' => $chatSession->unit_id,
                'customer_id' => $chatSession->customer_id,
                'user_id' => null,
            ]);
            Message::factory()->count(2)->create([
                'company_id' => $chatSession->company_id,
                'unit_id' => $chatSession->unit_id,
                'customer_id' => null,
                'user_id' => $chatSession->user_id,
            ]);
        }
    }
}
