<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ScheduleBlockResource extends JsonResource
{
    public function toArray($request)
    {
        // Obter o fuso horário da unidade do usuário
        $userTimezone = Auth::user()->unit->unitSettings->timezone ?? 'UTC';

        // Para bloqueios, não devemos converter a data, apenas os horários
        // A data do bloqueio deve permanecer a mesma para comparação correta
        $blockDate = $this->block_date;

        // Converter horários se existirem
        $startTimeInUserTimezone = null;
        $endTimeInUserTimezone = null;

        if ($this->start_time) {
            // Criar datetime UTC e converter para fuso do usuário
            $startUtc = Carbon::parse($this->block_date)->setTimeFromTimeString($this->start_time);
            $startTimeInUserTimezone = $startUtc->copy()->setTimezone($userTimezone)->format('H:i');
        }

        if ($this->end_time) {
            // Criar datetime UTC e converter para fuso do usuário
            $endUtc = Carbon::parse($this->block_date)->setTimeFromTimeString($this->end_time);
            $endTimeInUserTimezone = $endUtc->copy()->setTimezone($userTimezone)->format('H:i');
        }

        return [
            'id' => $this->id,
            'block_date' => $blockDate instanceof Carbon ? $blockDate->format('Y-m-d') : $blockDate,
            'start_time' => $startTimeInUserTimezone,
            'end_time' => $endTimeInUserTimezone,
            'block_type' => $this->block_type instanceof \App\Enum\ScheduleBlockTypeEnum ? $this->block_type : $this->block_type,
            'reason' => $this->reason,
            'active' => $this->active,
            'company' => [
                'id' => $this->company->id,
                'name' => $this->company->name,
            ],
            'unit' => [
                'id' => $this->unit->id,
                'name' => $this->unit->name,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
        ];
    }
}
