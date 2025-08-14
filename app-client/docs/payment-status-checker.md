# Sistema de Verificação de Status de Pagamentos

Este sistema automatiza a verificação de status de pagamentos pendentes no gateway Asaas, executando verificações a cada 5 minutos e atualizando o status dos pagamentos no sistema.

## Comandos Disponíveis

### 1. Verificar Pagamentos Pendentes

Verifica pagamentos pendentes e cria jobs para validar status no Asaas.

```bash
# Verificar pagamentos pendentes (padrão: 50 por execução)
php artisan payments:check-pending

# Verificar com limite personalizado
php artisan payments:check-pending --limit=100

# Ver apenas estatísticas
php artisan payments:check-pending --stats
```

### 2. Limpar Pagamentos Expirados

Marca pagamentos pendentes expirados como EXPIRED.

```bash
# Marcar pagamentos expirados como EXPIRED
php artisan payments:clean-expired

# Executar em modo teste (sem fazer alterações)
php artisan payments:clean-expired --dry-run
```

## Processamento de Filas

Para processar os jobs de verificação de pagamentos:

```bash
php artisan queue:work --queue=payment-checks
```

## Arquitetura

O sistema utiliza uma arquitetura em camadas:

1. **Commands Artisan** - Executam as verificações e limpezas
2. **Service** - `PaymentStatusCheckerService` - Lógica de negócio para processar pagamentos
3. **Jobs** - `CheckPaymentStatusJob` - Processa cada pagamento individualmente de forma assíncrona
4. **Queue** - Sistema de filas do Laravel para gerenciar os jobs
