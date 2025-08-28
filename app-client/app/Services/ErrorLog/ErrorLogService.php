<?php

namespace App\Services\ErrorLog;

use App\Models\ErrorLog;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ErrorLogService
{
    /**
     * Log an error in the system
     *
     * @param Throwable $exception The exception to log
     * @param array $context Additional context information
     * @param string $level Error level (error, warning, info, etc)
     * @return ErrorLog
     */
    public function logError(Throwable $exception, array $context = [], string $level = 'error'): ErrorLog
    {
        $user = Auth::user();

        return ErrorLog::create([
            'company_id' => $user?->company_id,
            'unit_id' => $user?->unit_id,
            'user_id' => $user?->id,
            'message' => $exception->getMessage(),
            'stack_trace' => $exception->getTraceAsString(),
            'level' => $level,
            'context' => array_merge($context, [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'code' => $exception->getCode(),
            ]),
        ]);
    }

    /**
     * Log a custom error message
     *
     * @param string $message The error message
     * @param array $context Additional context information
     * @param string $level Error level (error, warning, info, etc)
     * @return ErrorLog
     */
    public function logCustomError(string $message, array $context = [], string $level = 'error'): ErrorLog
    {
        $user = Auth::user();

        return ErrorLog::create([
            'company_id' => $user?->company_id,
            'unit_id' => $user?->unit_id,
            'user_id' => $user?->id,
            'message' => $message,
            'level' => $level,
            'context' => $context,
        ]);
    }

    /**
     * Mark an error as resolved
     *
     * @param ErrorLog $errorLog
     * @return ErrorLog
     */
    public function resolveError(ErrorLog $errorLog): ErrorLog
    {
        $user = Auth::user();

        $errorLog->update([
            'resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => $user?->id,
        ]);

        return $errorLog->fresh();
    }

    /**
     * Get unresolved errors for a company
     *
     * @param int $companyId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUnresolvedErrors(int $companyId)
    {
        return ErrorLog::where('company_id', $companyId)
            ->where('resolved', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all errors for a company
     *
     * @param int $companyId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllErrors(int $companyId)
    {
        return ErrorLog::where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getLogs()
    {
        return ErrorLog::orderBy('created_at', 'desc')->limit(10)->get();
    }
}
