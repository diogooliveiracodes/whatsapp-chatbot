<?php

namespace App\Repositories;

use App\Models\AutomatedMessage;
use App\Enum\AutomatedMessageTypeEnum;
use Illuminate\Database\Eloquent\Collection;

class AutomatedMessageRepository
{
    public function __construct(
        protected AutomatedMessage $model
    ) {}

    public function findById(int $id): ?AutomatedMessage
    {
        return $this->model->with(['unit', 'user', 'company'])->find($id);
    }

    public function findByUnitId(int $unitId): Collection
    {
        return $this
            ->model
            ->with(['unit', 'user', 'company'])
            ->where('unit_id', $unitId)
            ->orderBy('name')
            ->get();
    }

    public function findByUnitIdAndType(int $unitId, AutomatedMessageTypeEnum $type): Collection
    {
        return $this
            ->model
            ->with(['unit', 'user', 'company'])
            ->where('unit_id', $unitId)
            ->where('type', $type)
            ->orderBy('name')
            ->get();
    }







    public function create(array $data): AutomatedMessage
    {
        $message = $this->model->create($data);
        return $message->load(['unit', 'user', 'company']);
    }

    public function update(AutomatedMessage $message, array $data): AutomatedMessage
    {
        $message->update($data);
        return $message->fresh(['unit', 'user', 'company']);
    }

    public function delete(AutomatedMessage $message): bool
    {
        return $message->delete();
    }






}
