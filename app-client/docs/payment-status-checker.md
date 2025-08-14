# Verificador de Status de Pagamentos

## Visão Geral

O sistema de verificação de status de pagamentos é responsável por monitorar pagamentos pendentes e atualizar automaticamente as assinaturas quando os pagamentos são confirmados.

## Componentes Principais

### 1. Comando CheckPendingPaymentsCommand

**Arquivo:** `app/Console/Commands/CheckPendingPaymentsCommand.php`

**Funcionalidade:**
- Verifica pagamentos pendentes no sistema
- Cria jobs para verificar o status no gateway de pagamento (Asaas)
- Atualiza automaticamente as assinaturas quando pagamentos são confirmados

**Uso:**
```bash
# Verificar pagamentos pendentes (padrão: 50 por execução)
php artisan payments:check-pending

# Definir limite personalizado
php artisan payments:check-pending --limit=100

# Ver apenas estatísticas
php artisan payments:check-pending --stats
```

### 2. Job CheckPaymentStatusJob

**Arquivo:** `app/Jobs/CheckPaymentStatusJob.php`

**Funcionalidade:**
- Verifica o status de um pagamento específico no Asaas
- Atualiza o status do pagamento no sistema
- **NOVO:** Atualiza automaticamente a assinatura quando o pagamento é confirmado

**Processo:**
1. Busca o pagamento pelo ID do Asaas
2. Verifica se ainda está pendente
3. Consulta o status no Asaas
4. Atualiza o status interno
5. **Se confirmado:** Chama `SignatureService::updateSignatureAfterPayment()`

### 3. Serviço PaymentStatusCheckerService

**Arquivo:** `app/Services/Payment/PaymentStatusCheckerService.php`

**Funcionalidade:**
- Gerencia o processamento de pagamentos pendentes
- Cria jobs para verificação
- Fornece estatísticas dos pagamentos pendentes

### 4. Serviço SignatureService

**Arquivo:** `app/Services/Signature/SignatureService.php`

**Método:** `updateSignatureAfterPayment(Signature $signature)`

**Funcionalidade:**
- Atualiza a assinatura após confirmação do pagamento
- Adiciona os dias de saldo conforme a duração do plano
- Gerencia assinaturas expiradas vs. válidas

## Fluxo de Atualização de Assinatura

### Quando um Pagamento é Confirmado:

1. **Verificação de Status:** O job verifica o status no Asaas
2. **Atualização do Pagamento:** Status é atualizado para "PAID"
3. **Busca da Assinatura:** Sistema busca a assinatura relacionada ao pagamento
4. **Cálculo da Nova Expiração:**
   - Se a assinatura atual ainda é válida: adiciona meses à data atual
   - Se a assinatura está expirada: cria nova data a partir de agora
5. **Atualização da Assinatura:** Status e data de expiração são atualizados

### Exemplo de Cálculo:

```php
// Assinatura válida (expira em 30 dias)
$currentExpiration = now()->addDays(30);
$planDuration = 1; // 1 mês
$newExpiration = $currentExpiration->addMonths($planDuration);

// Assinatura expirada
$currentExpiration = now()->subDays(1); // Expirada
$newExpiration = now()->addMonths($planDuration);
```

## Configuração

### Queue Configuration

Os jobs são processados na fila `payment-checks`. Certifique-se de que o worker está rodando:

```bash
php artisan queue:work --queue=payment-checks
```

### Agendamento (Opcional)

Para verificação automática, adicione ao `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Verificar pagamentos pendentes a cada 30 minutos
    $schedule->command('payments:check-pending --limit=100')
             ->everyThirtyMinutes()
             ->withoutOverlapping();
}
```

## Logs e Monitoramento

### Logs Importantes:

- **Job Criado:** `Job de verificação de pagamento criado`
- **Status Atualizado:** `Status do pagamento atualizado com sucesso`
- **Assinatura Atualizada:** `Assinatura atualizada com sucesso após pagamento`
- **Erros:** Todos os erros são logados com contexto completo

### Monitoramento:

```bash
# Ver logs de pagamentos
tail -f storage/logs/laravel.log | grep -E "(payment|assinatura)"

# Verificar jobs na fila
php artisan queue:monitor payment-checks
```

## Testes

### Testes Disponíveis:

1. **CheckPendingPaymentsCommandTest:** Testa o comando principal
2. **SignatureUpdateAfterPaymentTest:** Testa a atualização de assinaturas

### Executar Testes:

```bash
# Todos os testes de pagamento
php artisan test --filter=Payment

# Teste específico
php artisan test tests/Feature/CheckPendingPaymentsCommandTest.php
```

## Tratamento de Erros

### Retry Policy:
- Jobs são tentados até 3 vezes
- Backoff de 30 segundos entre tentativas
- Erros são logados com contexto completo

### Casos de Erro:
- Pagamento não encontrado
- Resposta inválida do Asaas
- Assinatura não encontrada
- Erros de banco de dados

## Integração com Frontend

### Botão "Verificar Status":
O botão na tela de pagamento (`payment.blade.php`) usa a mesma lógica:
- Chama `SignatureService::checkPaymentStatus()`
- Atualiza automaticamente a assinatura se confirmado
- Fornece feedback visual ao usuário

### Diferenças:
- **Comando:** Processa em background, sem feedback visual
- **Botão:** Processa síncrono, com feedback imediato
- **Lógica:** Ambos usam o mesmo `SignatureService::updateSignatureAfterPayment()`

## Benefícios da Implementação

1. **Automatização:** Assinaturas são atualizadas automaticamente
2. **Consistência:** Mesma lógica usada no comando e no botão
3. **Confiabilidade:** Jobs com retry e logging completo
4. **Escalabilidade:** Processamento em background
5. **Monitoramento:** Logs detalhados para debugging

## Próximos Passos

1. Configurar agendamento automático
2. Implementar notificações por email
3. Adicionar dashboard de monitoramento
4. Implementar webhooks do Asaas para atualização em tempo real
