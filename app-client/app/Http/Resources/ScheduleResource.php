<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ScheduleResource extends JsonResource
{
    public function toArray($request)
    {
        $start = Carbon::parse($this->schedule_date)->setTimeFromTimeString($this->start_time);
        $end = Carbon::parse($this->schedule_date)->setTimeFromTimeString($this->end_time);

        return [
            'id' => $this->id,
            'title' => $this->customer->name,
            'start' => $start->format('Y-m-d\TH:i:s'),
            'end' => $end->format('Y-m-d\TH:i:s'),
            'status' => $this->status,
            'service_type' => $this->service_type,
            'notes' => $this->notes,
            'customer' => [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
        ];
    }
}
