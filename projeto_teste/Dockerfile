# Use uma imagem base do PHP
FROM php:8.1-fpm

# Instalar dependências e extensões do PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Instalar o Composer (gerenciador de dependências PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Definir o diretório de trabalho
WORKDIR /var/www

# Copiar os arquivos do Laravel para dentro do contêiner
COPY . .

# Instalar as dependências do Laravel
RUN composer install

# Expôr a porta 80 para o servidor web
EXPOSE 80

# Comando para rodar o servidor PHP embutido
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]