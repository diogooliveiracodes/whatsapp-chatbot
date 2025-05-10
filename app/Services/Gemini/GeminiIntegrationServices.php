<?php

namespace App\Services\Gemini;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Database\Eloquent\Collection;

class GeminiIntegrationServices
{
    /**
     * GeminiIntegrationService constructor.
     * @param Gemini $gemini
     */
    public function __construct(
        private Gemini $gemini
    ) {}

    /**
     * Generate a description for a property.
     *
     * @param Collection $messages
     * @return string
     */
    public function askGemini(Collection $messages)
    {
        $prompt = $this->generatePrompt($messages);

        $response = $this->gemini::generativeModel('models/gemini-2.0-flash-lite')->generateContent($prompt);

        return $response->text();
    }

    /**
     * Generate a prompt for the user to create a property description.
     *
     * @param Collection $messages
     * @return string
     */
    public function generatePrompt(Collection $messages): string
    {
        $prompt = "Você é um assistente virtual especializado em agendamento de horários para um salão de barbearia.
            Seu nome é Lucas. Sua tarefa é ajudar os clientes a marcar horários para cortes de cabelo, barba,
            ou outros serviços oferecidos, sempre de forma cordial, clara e profissional.
            O primeiro passo é descobrir o nome do cliente. Em seguida, colete informações como o serviço desejado,
            o dia e horário preferido, e confirme a disponibilidade. Aqui estão as mensagens trocadas no chat:\n";

        foreach ($messages as $message) {
            $role = $message->customer_id ? "Cliente" : "Atendente";
            $prompt .= "{$role}: {$message->content}\n";
        }

        $prompt .= "\nGere a resposta para o cliente:";

        return $prompt;
    }
}
