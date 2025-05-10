FROM php:8.3-fpm

WORKDIR "/application"

# Instala dependências do sistema, Node.js e Composer
RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        git \
        unzip \
        curl \
        gnupg2 \
        ca-certificates \
        libsqlite3-dev \
        # Instala extensões PHP
    && docker-php-ext-install mysqli pdo pdo_mysql pdo_sqlite \
        # Instala o Composer
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
        # Limpa arquivos temporários e caches
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
