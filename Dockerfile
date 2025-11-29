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

# Exponer puerto
EXPOSE 8080

# Usar servidor PHP sin invocar artisan
CMD php -S 0.0.0.0:${PORT:-8080} server.php
