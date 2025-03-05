# WhatsApp Chatbot

 Este projeto é uma aplicação desenvolvida em Laravel 12 para criar um chatbot integrado à API do WhatsApp, utilizando a IA do Gemini e interagindo com o GroupCRM.

## Tecnologias Utilizadas:
- Laravel 12 - Framework PHP para desenvolvimento web.
- API do WhatsApp - Para envio e recebimento de mensagens.
- Gemini AI - Para processamento de linguagem natural.
- MySQL - Banco de dados relacional.

<hr>

## Instalação
1. Clone este repositório:
```
git clone https://github.com/diogooliveiracodes/whatsapp-chatbot.git
cd whatsapp-chatbot
```
2. Instale as depenências:
```
composer install
```
3. Configure o arquivo .env:
```
cp .env.example .env
```
4. Gere a chave da aplicação:
```
php artisan key:generate
```
5. Execute as migrações:
```
php artisan migrate
```
6. Inicie o servidor local:
```
php artisan serve
```
