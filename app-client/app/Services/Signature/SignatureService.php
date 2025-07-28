<?php

namespace App\Services\Signature;

use App\Models\Signature;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use App\Enum\SignatureStatusEnum;

class SignatureService
{
    public function activateTrial(array $data): void
    {
        $plan = Plan::find($data['plan_id']);

        Signature::create([
            'company_id' => $data['company_id'],
            'plan_id' => $data['plan_id'],
            'status' => SignatureStatusEnum::PAID->value,
            'expires_at' => now()->addMonths($plan->duration_months),
        ]);
    }
}
