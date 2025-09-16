### Lembrete de deploy

- Configurar o php-ini para o upload de imagens upload_max_filesize = 20M, post_max_size = 20M
```
sudo nano /etc/php/8.3/apache2/php.ini
sudo systemctl restart apache2
```

- Instalar o php-gd:
```sudo apt-get install php-gd```