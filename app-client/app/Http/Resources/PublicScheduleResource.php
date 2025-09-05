<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PublicScheduleResource extends JsonResource
{
    public function toArray($request)
    {
        // Use UTC timezone for public access
        $timezone = 'UTC';

        // Create dates in UTC (as they are saved in database)
        $startUtc = Carbon::parse($this->schedule_date)->setTimeFromTimeString($this->start_time);
        $endUtc = Carbon::parse($this->schedule_date)->setTimeFromTimeString($this->end_time);

        // Convert to the specified timezone
        $startInTimezone = $startUtc->copy()->setTimezone($timezone);
        $endInTimezone = $endUtc->copy()->setTimezone($timezone);

        return [
            'id' => $this->id,
            'title' => $this->customer->name,
            'start' => $startInTimezone->format('Y-m-d\TH:i:s'),
            'end' => $endInTimezone->format('Y-m-d\TH:i:s'),
            'start_time' => $startInTimezone->format('H:i'),
            'end_time' => $endInTimezone->format('H:i'),
            'schedule_date' => $startInTimezone->format('Y-m-d'),
            'status' => $this->status,
            'notes' => $this->notes,
            'customer' => [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
                'phone' => $this->customer->phone,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'unit' => [
                'id' => $this->unit->id,
                'name' => $this->unit->name,
            ],
            'unit_service_type' => [
                'id' => $this->unitServiceType->id,
                'name' => $this->unitServiceType->name,
                'price' => $this->unitServiceType->price,
            ],
        ];
    }
}
