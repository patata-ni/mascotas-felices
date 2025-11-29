FROM php:8.2-cli

# Instalar extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
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
RUN chmod -R 775 storage bootstrap/cache

# Crear .env desde ejemplo si no existe
RUN cp .env.example .env || true

# Generar APP_KEY
RUN php artisan key:generate --force --no-interaction || true

# Exponer puerto
EXPOSE 8080

# Comando de inicio
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan migrate --force --no-interaction && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
