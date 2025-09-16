# Guia para o deploy

## Configurações

### Instalar o php-gd:

`sudo apt-get install php-gd`

### Configurar o php-ini para o upload de imagens upload_max_filesize = 20M, post_max_size = 20M

```
sudo nano /etc/php/8.3/apache2/php.ini
sudo systemctl restart apache2
```

### Liberar permissões no usuário ubuntu na pasta storage:

```
sudo chown -R www-data:www-data /var/www/whatsapp-chatbot/app-client/storage
sudo chown -R www-data:www-data /var/www/whatsapp-chatbot/app-client/bootstrap/cache
sudo chmod -R 775 /var/www/whatsapp-chatbot/app-client/storage
sudo chmod -R 775 /var/www/whatsapp-chatbot/app-client/bootstrap/cache
```

## Criar o cronjob:

```
sudo crontab -e
```

- selecionar 1 para editar o cronjob com nano e adicionar a seguinte linha:

```
*/1 * * * * sudo -u www-data /usr/bin/php /var/www/whatsapp-chatbot/app-client/artisan payments:check-pending >> /var/www/whatsapp-chatbot/app-client/storage/logs/cron.log 2>&1
```

- Iniciar o cronjob:

```
sudo service cron restart
```

- Verificar se o cronjob está rodando:

```
sudo crontab -l
```

- Verificar os logs do cronjob:

```
sudo tail -f /var/www/whatsapp-chatbot/app-client/storage/logs/cron.log
```

## Instalar o SUPERVISOR:
- O supervisor é um sistema de monitoramento e controle de processos em background. Vamos criar duas configurações de fila de processamento.

```
sudo apt update
sudo apt install supervisor -y
```

### Criar um arquivo de configuração para a fila principal de processamento:

`sudo nano /etc/supervisor/conf.d/laravel-worker.conf`

### Adicionar o seguinte conteúdo:

```
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=/usr/bin/php /var/www/whatsapp-chatbot/app-client/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/whatsapp-chatbot/app-client/storage/logs/queue.log
```

### Criar um arquivo de configuração para a fila de pagamentos:

`sudo nano /etc/supervisor/conf.d/laravel-payment-checks.conf`

### Adicionar o seguinte conteúdo:
```
[program:laravel-payment-checks]
process_name=%(program_name)s_%(process_num)02d
command=/usr/bin/php /var/www/whatsapp-chatbot/app-client/artisan queue:work --queue=payment-checks --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/whatsapp-chatbot/app-client/storage/logs/payment-checks-queue.log
```

### Atualizar e iniciar o Supervisor:

```
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
sudo supervisorctl start laravel-payment-checks:*
```

### Verificar se o supervisor está rodando:

```
sudo supervisorctl status
```

### Verificar os logs do supervisor:

```
sudo tail -f /var/www/whatsapp-chatbot/app-client/storage/logs/queue.log
sudo tail -f /var/www/whatsapp-chatbot/app-client/storage/logs/payment-checks-queue.log
```

### Verificar os logs do  worker:
```
tail -f /var/www/whatsapp-chatbot/app-client/storage/logs/queue.log
````