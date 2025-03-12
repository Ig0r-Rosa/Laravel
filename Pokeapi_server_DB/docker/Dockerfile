# Usar uma imagem oficial do PHP com FPM (FastCGI Process Manager)
FROM php:8.1-fpm

# Instalar dependências necessárias, como o servidor web Nginx (opcional se for PHP com servidor embutido)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git

# Copiar o código do projeto para dentro do contêiner
COPY ../app /var/www/html

# Expor a porta 9000 para o PHP-FPM
EXPOSE 9000

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Rodar o servidor PHP embutido (não é necessário se usar Nginx, por exemplo)
CMD ["php", "-S", "0.0.0.0:9000"]