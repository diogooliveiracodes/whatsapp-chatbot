<?php

namespace App\Services\AutomatedMessage;

use App\Models\AutomatedMessage;
use App\Repositories\AutomatedMessageRepository;
use App\Enum\AutomatedMessageTypeEnum;
use App\Services\ErrorLog\ErrorLogService;
use App\Services\Http\HttpResponseService;
use App\Exceptions\AutomatedMessage\AutomatedMessageException;
use App\Exceptions\AutomatedMessage\AutomatedMessageNotFoundException;
use App\Exceptions\AutomatedMessage\AutomatedMessageValidationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AutomatedMessageService
{
    public function __construct(
        protected AutomatedMessageRepository $automatedMessageRepository,
        protected ErrorLogService $errorLogService,
        protected HttpResponseService $httpResponse
    ) {}

    /**
     * Get all automated messages for a unit
     */
    public function getMessagesByUnit(int $unitId): Collection
    {
        try {
            return $this->automatedMessageRepository->findByUnitId($unitId);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'unit_id' => $unitId,
                'user_id' => Auth::id()
            ]);
            throw new AutomatedMessageException(__('automated-messages.messages.load_error'));
        }
    }



    /**
     * Get automated messages by type for a unit
     */
    public function getMessagesByUnitAndType(int $unitId, AutomatedMessageTypeEnum $type): Collection
    {
        try {
            return $this->automatedMessageRepository->findByUnitIdAndType($unitId, $type);
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'unit_id' => $unitId,
                'type' => $type->value,
                'user_id' => Auth::id()
            ]);
            throw new AutomatedMessageException(__('automated-messages.messages.load_error'));
        }
    }

    /**
     * Get automated message by ID
     */
    public function getMessageById(int $id): AutomatedMessage
    {
        try {
            $message = $this->automatedMessageRepository->findById($id);

            if (!$message) {
                throw new AutomatedMessageNotFoundException(__('automated-messages.messages.not_found'));
            }

            return $message;
        } catch (AutomatedMessageNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'message_id' => $id,
                'user_id' => Auth::id()
            ]);
            throw new AutomatedMessageException(__('automated-messages.messages.load_error'));
        }
    }

    /**
     * Create a new automated message
     */
    public function createMessage(array $data): AutomatedMessage
    {
        try {
            $data['user_id'] = Auth::id();

            // Obter o company_id da unidade se não foi fornecido
            if (!isset($data['company_id'])) {
                $unit = \App\Models\Unit::find($data['unit_id']);
                if ($unit) {
                    $data['company_id'] = $unit->company_id;
                }
            }

            $message = $this->automatedMessageRepository->create($data);

            Log::info('Automated message created', [
                'message_id' => $message->id,
                'user_id' => Auth::id(),
                'unit_id' => $data['unit_id'],
                'company_id' => $data['company_id'] ?? null
            ]);

            return $message;
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'data' => $data,
                'user_id' => Auth::id()
            ]);
            throw new AutomatedMessageException(__('automated-messages.messages.create_error', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Update an automated message
     */
    public function updateMessage(AutomatedMessage $message, array $data): AutomatedMessage
    {
        try {
            $updatedMessage = $this->automatedMessageRepository->update($message, $data);

            Log::info('Automated message updated', [
                'message_id' => $message->id,
                'user_id' => Auth::id()
            ]);

            return $updatedMessage;
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'message_id' => $message->id,
                'data' => $data,
                'user_id' => Auth::id()
            ]);
            throw new AutomatedMessageException(__('automated-messages.messages.update_error', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Delete an automated message
     */
    public function deleteMessage(AutomatedMessage $message): bool
    {
        try {
            $result = $this->automatedMessageRepository->delete($message);

            if ($result) {
                Log::info('Automated message deleted', [
                    'message_id' => $message->id,
                    'user_id' => Auth::id()
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            $this->errorLogService->logError($e, [
                'message_id' => $message->id,
                'user_id' => Auth::id()
            ]);
            throw new AutomatedMessageException(__('automated-messages.messages.delete_error', ['message' => $e->getMessage()]));
        }
    }



    /**
     * Get all message types
     */
    public function getMessageTypes(): array
    {
        return AutomatedMessageTypeEnum::cases();
    }

    /**
     * Validate message data
     */
    public function validateMessageData(array $data): void
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = __('automated-messages.messages.name_required');
        } elseif (strlen($data['name']) > 255) {
            $errors[] = __('automated-messages.messages.name_max');
        }

        if (empty($data['type'])) {
            $errors[] = __('automated-messages.messages.type_required');
        } elseif (!in_array($data['type'], AutomatedMessageTypeEnum::values())) {
            $errors[] = __('automated-messages.messages.type_invalid');
        }

        if (empty($data['content'])) {
            $errors[] = __('automated-messages.messages.content_required');
        } elseif (strlen($data['content']) > 1000) {
            $errors[] = __('automated-messages.messages.content_max');
        }

        if (empty($data['unit_id'])) {
            $errors[] = __('automated-messages.messages.unit_required');
        }

        if (!empty($errors)) {
            throw new AutomatedMessageValidationException(implode(', ', $errors));
        }
    }




}
