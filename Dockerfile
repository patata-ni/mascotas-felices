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

# Copiar .env.example a .env
RUN cp .env.example .env

# Generar APP_KEY
RUN php artisan key:generate --force

# Exponer puerto
EXPOSE 8080

# Usar bash para manejar variables
CMD ["bash", "-c", "php -S 0.0.0.0:${PORT} -t public"]
