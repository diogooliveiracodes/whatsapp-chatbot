<?php

namespace App\Services\AutomatedMessage;

use App\Models\Customer;
use App\Models\Schedule;
use App\Models\Payment;
use App\Models\Unit;
use App\Models\Company;

class AutomatedMessageProcessorService
{
    /**
     * Process a message content by replacing variables with actual values
     *
     * @param string $content The message content with variables
     * @param array $data The data to replace variables with
     * @return string The processed message content
     */
    public function processMessage(string $content, array $data = []): string
    {
        $processedContent = $content;

        // Replace customer variables
        if (isset($data['customer'])) {
            $processedContent = $this->replaceCustomerVariables($processedContent, $data['customer']);
        }

        // Replace schedule variables
        if (isset($data['schedule'])) {
            $processedContent = $this->replaceScheduleVariables($processedContent, $data['schedule']);
        }

        // Replace payment variables
        if (isset($data['payment'])) {
            $processedContent = $this->replacePaymentVariables($processedContent, $data['payment']);
        }

        // Replace unit variables
        if (isset($data['unit'])) {
            $processedContent = $this->replaceUnitVariables($processedContent, $data['unit']);
        }

        // Replace company variables
        if (isset($data['company'])) {
            $processedContent = $this->replaceCompanyVariables($processedContent, $data['company']);
        }

        return $processedContent;
    }

    /**
     * Replace customer-related variables in the message
     */
    private function replaceCustomerVariables(string $content, $customer): string
    {
        $replacements = [];

        if ($customer instanceof Customer) {
            $replacements = [
                '{customer_name}' => $customer->name ?? 'Cliente',
                '{customer_phone}' => $customer->phone ?? '',
            ];
        } elseif (is_array($customer)) {
            $replacements = [
                '{customer_name}' => $customer['name'] ?? 'Cliente',
                '{customer_phone}' => $customer['phone'] ?? '',
            ];
        }

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    /**
     * Replace schedule-related variables in the message
     */
    private function replaceScheduleVariables(string $content, $schedule): string
    {
        $replacements = [];

        if ($schedule instanceof Schedule) {
            $replacements = [
                '{schedule_date}' => $schedule->schedule_date ? $schedule->schedule_date->format('d/m/Y') : '',
                '{schedule_time}' => $schedule->start_time ?? '',
                '{service_name}' => $schedule->unitServiceType->name ?? '',
            ];
        } elseif (is_array($schedule)) {
            $replacements = [
                '{schedule_date}' => isset($schedule['schedule_date']) ? date('d/m/Y', strtotime($schedule['schedule_date'])) : '',
                '{schedule_time}' => $schedule['start_time'] ?? '',
                '{service_name}' => $schedule['service_name'] ?? '',
            ];
        }

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    /**
     * Replace payment-related variables in the message
     */
    private function replacePaymentVariables(string $content, $payment): string
    {
        $replacements = [];

        if ($payment instanceof Payment) {
            $replacements = [
                '{payment_amount}' => $this->formatCurrency($payment->amount ?? 0),
                '{payment_method}' => $payment->payment_method ?? '',
            ];
        } elseif (is_array($payment)) {
            $replacements = [
                '{payment_amount}' => $this->formatCurrency($payment['amount'] ?? 0),
                '{payment_method}' => $payment['payment_method'] ?? '',
            ];
        }

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    /**
     * Replace unit-related variables in the message
     */
    private function replaceUnitVariables(string $content, $unit): string
    {
        $replacements = [];

        if ($unit instanceof Unit) {
            $replacements = [
                '{unit_name}' => $unit->name ?? '',
            ];
        } elseif (is_array($unit)) {
            $replacements = [
                '{unit_name}' => $unit['name'] ?? '',
            ];
        }

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    /**
     * Replace company-related variables in the message
     */
    private function replaceCompanyVariables(string $content, $company): string
    {
        $replacements = [];

        if ($company instanceof Company) {
            $replacements = [
                '{company_name}' => $company->name ?? '',
            ];
        } elseif (is_array($company)) {
            $replacements = [
                '{company_name}' => $company['name'] ?? '',
            ];
        }

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    /**
     * Format currency value
     */
    private function formatCurrency(float $amount): string
    {
        return 'R$ ' . number_format($amount, 2, ',', '.');
    }

    /**
     * Process a message for a specific context (schedule confirmation, payment, etc.)
     *
     * @param string $content The message content
     * @param string $context The context (schedule, payment, etc.)
     * @param array $contextData The context-specific data
     * @return string The processed message
     */
    public function processMessageForContext(string $content, string $context, array $contextData = []): string
    {
        switch ($context) {
            case 'schedule_confirmation':
            case 'schedule_reminder':
            case 'schedule_cancellation':
                return $this->processScheduleMessage($content, $contextData);

            case 'payment_confirmation':
            case 'payment_reminder':
                return $this->processPaymentMessage($content, $contextData);

            case 'welcome_message':
                return $this->processWelcomeMessage($content, $contextData);

            case 'custom_message':
                return $this->processCustomMessage($content, $contextData);

            default:
                return $this->processMessage($content, $contextData);
        }
    }

    /**
     * Process a schedule-related message
     */
    private function processScheduleMessage(string $content, array $data): string
    {
        $processedData = [];

        if (isset($data['customer'])) {
            $processedData['customer'] = $data['customer'];
        }

        if (isset($data['schedule'])) {
            $processedData['schedule'] = $data['schedule'];
        }

        if (isset($data['unit'])) {
            $processedData['unit'] = $data['unit'];
        }

        if (isset($data['company'])) {
            $processedData['company'] = $data['company'];
        }

        return $this->processMessage($content, $processedData);
    }

    /**
     * Process a payment-related message
     */
    private function processPaymentMessage(string $content, array $data): string
    {
        $processedData = [];

        if (isset($data['customer'])) {
            $processedData['customer'] = $data['customer'];
        }

        if (isset($data['payment'])) {
            $processedData['payment'] = $data['payment'];
        }

        if (isset($data['unit'])) {
            $processedData['unit'] = $data['unit'];
        }

        if (isset($data['company'])) {
            $processedData['company'] = $data['company'];
        }

        return $this->processMessage($content, $processedData);
    }

    /**
     * Process a welcome message
     */
    private function processWelcomeMessage(string $content, array $data): string
    {
        $processedData = [];

        if (isset($data['customer'])) {
            $processedData['customer'] = $data['customer'];
        }

        if (isset($data['unit'])) {
            $processedData['unit'] = $data['unit'];
        }

        if (isset($data['company'])) {
            $processedData['company'] = $data['company'];
        }

        return $this->processMessage($content, $processedData);
    }

    /**
     * Process a custom message
     */
    private function processCustomMessage(string $content, array $data): string
    {
        return $this->processMessage($content, $data);
    }
}
