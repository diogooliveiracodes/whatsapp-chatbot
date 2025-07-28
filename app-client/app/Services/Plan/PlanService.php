<?php

namespace App\Services\Plan;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;

class PlanService
{
    /**
     * Get all plans.
     *
     * Retrieves all plans from the database.
     *
     * @return Collection The collection of plans
     */
    public function getPlans(): Collection
    {
        return Plan::all();
    }
}
