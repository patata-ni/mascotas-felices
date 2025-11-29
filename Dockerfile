FROM php:8.2-cli

# Instalar extensiones PHP necesarias y herramientas
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    git \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /app

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configurar permisos
RUN chmod -R 777 storage bootstrap/cache

# Exponer puerto
EXPOSE 8080

# Crear script de inicio
RUN echo '#!/bin/sh\n\
if [ ! -f .env ]; then\n\
    cp .env.example .env\n\
    php artisan key:generate --force\n\
fi\n\
php artisan config:clear\n\
php artisan migrate --force || true\n\
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}\n\
' > /start.sh && chmod +x /start.sh

CMD ["/start.sh"]
