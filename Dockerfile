FROM php:8.2-apache

# Instalar extensiones PHP necesarias y herramientas
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /app

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configurar permisos (después de composer install)
RUN mkdir -p storage/logs storage/framework/{sessions,views,cache} \
    && chmod -R 777 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Crear un .env básico en build time
RUN echo "APP_NAME=MascotasFelices\n\
APP_ENV=production\n\
APP_DEBUG=false\n\
APP_URL=http://localhost\n\
\n\
LOG_CHANNEL=stack\n\
\n\
DB_CONNECTION=mysql\n\
\n\
SESSION_DRIVER=file\n\
SESSION_LIFETIME=120\n\
" > .env

# Configurar Apache
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /app/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configurar puerto dinámico para Apache y ejecutar migraciones
RUN printf '#!/bin/sh\necho "Running migrations..."\nphp artisan migrate --force\necho "Running seeders..."\nphp artisan db:seed --force\necho "Starting Apache..."\nPORT=${PORT:-80}\nsed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf\nsed -i "s/:80/:$PORT/" /etc/apache2/sites-available/000-default.conf\napache2-foreground\n' > /start.sh && chmod +x /start.sh

# Exponer puerto
EXPOSE 80

CMD ["/start.sh"]
