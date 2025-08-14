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
    protected $description = 'Verifica pagamentos pendentes e cria jobs para validar status no Asaas';

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

        $result = $paymentStatusCheckerService->processPendingPayments($limit);

        if ($result['success']) {
            $this->info($result['message']);

            if ($result['processed'] > 0) {
                $this->table(
                    ['Processados', 'Erros', 'Total Encontrado'],
                    [[$result['processed'], $result['errors'], $result['total_found']]]
                );
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
    }
}
