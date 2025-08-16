<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ScheduleResource extends JsonResource
{
    public function toArray($request)
    {
        // Obter o fuso horário da unidade do usuário
        $userTimezone = Auth::user()->unit->unitSettings->timezone ?? 'UTC';

        // Criar as datas em UTC (como estão salvas no banco)
        $startUtc = Carbon::parse($this->schedule_date)->setTimeFromTimeString($this->start_time);
        $endUtc = Carbon::parse($this->schedule_date)->setTimeFromTimeString($this->end_time);

        // Converter para o fuso horário do usuário
        $startInUserTimezone = $startUtc->copy()->setTimezone($userTimezone);
        $endInUserTimezone = $endUtc->copy()->setTimezone($userTimezone);

        return [
            'id' => $this->id,
            'title' => $this->customer->name,
            'start' => $startInUserTimezone->format('Y-m-d\TH:i:s'),
            'end' => $endInUserTimezone->format('Y-m-d\TH:i:s'),
            'start_time' => $startInUserTimezone->format('H:i'),
            'end_time' => $endInUserTimezone->format('H:i'),
            'schedule_date' => $startInUserTimezone->format('Y-m-d'),
            'status' => $this->status,
            'notes' => $this->notes,
            'customer' => [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'unit_service_type' => [
                'id' => $this->unitServiceType->id,
                'name' => $this->unitServiceType->name,
            ],
        ];
    }
}
