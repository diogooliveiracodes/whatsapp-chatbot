<?php

namespace App\Console\Commands;

use App\Services\Payment\PaymentStatusCheckerService;
use Illuminate\Console\Command;

class CheckPendingPaymentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:check-pending {--limit=50 : Limite de pagamentos para processar por execução} {--stats : Mostrar apenas estatísticas}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica pagamentos pendentes, cria jobs para validar status no Asaas e atualiza assinaturas quando pagamentos são confirmados';

    /**
     * Execute the console command.
     */
    public function handle(PaymentStatusCheckerService $paymentStatusCheckerService)
    {
        if ($this->option('stats')) {
            $this->showStats($paymentStatusCheckerService);
            return 0;
        }

        $limit = (int) $this->option('limit');

        $this->info("Iniciando verificação de pagamentos pendentes...");
        $this->info("Os jobs criados irão verificar o status dos pagamentos no Asaas e atualizar as assinaturas automaticamente quando os pagamentos forem confirmados.");

        $result = $paymentStatusCheckerService->processPendingPayments($limit);

        if ($result['success']) {
            $this->info($result['message']);

            if ($result['processed'] > 0) {
                $this->table(
                    ['Processados', 'Erros', 'Total Encontrado'],
                    [[$result['processed'], $result['errors'], $result['total_found']]]
                );

                $this->info("ℹ️  Os jobs irão processar os pagamentos em background e atualizar automaticamente as assinaturas quando os pagamentos forem confirmados.");
                $this->info("📅 As assinaturas terão seus dias de saldo adicionados conforme a duração do plano.");
            }
        } else {
            $this->error($result['message']);
            return 1;
        }

        return 0;
    }

    /**
     * Mostra estatísticas dos pagamentos pendentes
     */
    private function showStats(PaymentStatusCheckerService $paymentStatusCheckerService): void
    {
        $this->info("Estatísticas dos Pagamentos Pendentes");
        $this->info("=====================================");

        $stats = $paymentStatusCheckerService->getPendingPaymentsStats();

        if (isset($stats['error'])) {
            $this->error("Erro ao obter estatísticas: " . $stats['error']);
            return;
        }

        $this->table(
            ['Total Pendentes', 'Expirados', 'Válidos', 'Última Verificação'],
            [[
                $stats['total_pending'],
                $stats['expired_pending'],
                $stats['valid_pending'],
                $stats['last_check']
            ]]
        );

        $this->info("ℹ️  Execute o comando sem --stats para processar os pagamentos pendentes.");
        $this->info("📅 As assinaturas serão atualizadas automaticamente quando os pagamentos forem confirmados.");
    }
}
