# Verificador de Status de Pagamentos

## Como Usar

### 1. Executar o Comando

```bash
# Verificar pagamentos pendentes (padrão: 50 por execução)
php artisan payments:check-pending

# Definir limite personalizado
php artisan payments:check-pending --limit=100

# Ver apenas estatísticas
php artisan payments:check-pending --stats
```

### 2. Iniciar a Fila de Processamento

```bash
# Iniciar worker da fila payment-checks
php artisan queue:work --queue=payment-checks

# Manter o worker rodando em background
php artisan queue:work --queue=payment-checks --daemon
```

### 3. Monitorar a Fila

```bash
# Verificar status da fila
php artisan queue:monitor payment-checks

# Ver logs de pagamentos
tail -f storage/logs/laravel.log | grep -E "(payment|assinatura)"
```

## O que o Sistema Faz

- Verifica pagamentos pendentes no sistema
- Consulta o status no gateway de pagamento (Asaas)
- Atualiza automaticamente as assinaturas quando pagamentos são confirmados
- Processa tudo em background usando filas
