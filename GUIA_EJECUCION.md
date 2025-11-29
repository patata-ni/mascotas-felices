# Guía de Ejecución - Sistema Mascotas Felices

## 📋 Pre-requisitos

Antes de comenzar, asegúrate de tener instalado:

- ✅ XAMPP (Apache y MySQL activos)
- ✅ PHP 8.1 o superior
- ✅ Composer
- ✅ Git (opcional)

## 🚀 Pasos para Ejecutar el Proyecto

### 1. Verificar XAMPP

Asegúrate de que XAMPP esté corriendo:

1. Abre XAMPP Control Panel
2. Inicia **Apache**
3. Inicia **MySQL**

### 2. Crear la Base de Datos

1. Abre tu navegador y ve a: `http://localhost/phpmyadmin`
2. Crea una nueva base de datos llamada: `mascotas_felices`
3. Selecciona el cotejamiento: `utf8mb4_unicode_ci`

**O ejecuta este comando SQL en phpMyAdmin**:
```sql
CREATE DATABASE mascotas_felices CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Configurar el Archivo .env

1. En la carpeta del proyecto, busca el archivo `.env`
2. Si no existe, copia `.env.example` a `.env`
3. Abre `.env` y configura estos valores:

```env
APP_NAME="Mascotas Felices"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mascotas_felices
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Instalar Dependencias

Abre la terminal en la carpeta del proyecto y ejecuta:

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/MascotasFelices
composer install
```

### 5. Generar la Clave de Aplicación

```bash
php artisan key:generate
```

### 6. Ejecutar las Migraciones

Este comando creará todas las tablas en la base de datos:

```bash
php artisan migrate
```

Deberías ver algo como:
```
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table (XX.XXms)
...
```

### 7. Poblar la Base de Datos (Seeders)

Este comando insertará datos de prueba:

```bash
php artisan db:seed
```

Al finalizar verás:
```
Base de datos poblada exitosamente!
Usuario admin: admin@mascotasfelices.com
Usuario vendedor: vendedor@mascotasfelices.com
Usuario inventario: inventario@mascotasfelices.com
Contraseña para todos: password
```

### 8. Crear Enlace Simbólico para Imágenes

```bash
php artisan storage:link
```

### 9. Iniciar el Servidor de Desarrollo

```bash
php artisan serve
```

Deberías ver:
```
Starting Laravel development server: http://127.0.0.1:8000
```

### 10. Acceder al Sistema

Abre tu navegador y ve a: `http://127.0.0.1:8000`

## 👥 Credenciales de Acceso

### Usuario Administrador
- **Email**: admin@mascotasfelices.com
- **Contraseña**: password
- **Permisos**: Acceso completo al sistema

### Usuario Vendedor
- **Email**: vendedor@mascotasfelices.com
- **Contraseña**: password
- **Permisos**: Ventas y consulta de clientes

### Usuario Inventario
- **Email**: inventario@mascotasfelices.com
- **Contraseña**: password
- **Permisos**: Productos, proveedores y pedidos

## 🔧 Comandos Útiles

### Ver rutas disponibles
```bash
php artisan route:list
```

### Limpiar caché
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Recrear la base de datos (¡CUIDADO! Borra todo)
```bash
php artisan migrate:fresh --seed
```

### Ejecutar el servidor en otro puerto
```bash
php artisan serve --port=8080
```

## 📱 Probar la API

### Usando curl (Terminal)

**Login**:
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@mascotasfelices.com",
    "password": "password"
  }'
```

**Obtener productos** (reemplaza {TOKEN} con el token obtenido):
```bash
curl -X GET http://127.0.0.1:8000/api/productos \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json"
```

### Usando Postman

1. Descarga e instala Postman
2. Importa la colección de endpoints (ver API_DOCUMENTATION.md)
3. Configura las variables:
   - `base_url`: http://127.0.0.1:8000/api
   - `token`: (se obtiene después del login)

## 🐛 Solución de Problemas Comunes

### Error: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Error: "SQLSTATE[HY000] [1049] Unknown database"
- Verifica que la base de datos `mascotas_felices` exista en phpMyAdmin
- Verifica las credenciales en el archivo `.env`

### Error: "Class 'PDO' not found"
- Habilita la extensión `extension=pdo_mysql` en `php.ini`
- Reinicia Apache desde XAMPP

### Error: "Permission denied" al crear storage link
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### El servidor no inicia en el puerto 8000
- Verifica que el puerto 8000 no esté en uso
- Usa otro puerto: `php artisan serve --port=8080`

### Las migraciones no se ejecutan
```bash
# Verifica que MySQL esté corriendo
# Verifica las credenciales del .env
# Intenta conectar manualmente a MySQL:
mysql -u root -p
```

## 📊 Verificar que Todo Funciona

### 1. Base de Datos
```bash
php artisan tinker
```
Luego ejecuta:
```php
\App\Models\User::count();  // Debería retornar 3
\App\Models\Producto::count();  // Debería retornar 8
\App\Models\Cliente::count();  // Debería retornar 3
```

### 2. API
Haz una petición de login y verifica que obtienes un token.

### 3. Aplicación Web
- Accede a `http://127.0.0.1:8000`
- Inicia sesión con cualquier usuario de prueba
- Verifica que puedes navegar por el sistema

## 📝 Notas Importantes

1. **Nunca ejecutes** `migrate:fresh` en producción (borra todos los datos)
2. Los **seeders** solo deben ejecutarse una vez o en desarrollo
3. El archivo **.env** nunca debe subirse a Git (ya está en .gitignore)
4. Para **producción**, cambia `APP_DEBUG=false` en el .env
5. El **storage** necesita permisos de escritura

## 🆘 ¿Necesitas Ayuda?

Si encuentras algún problema:

1. Revisa los logs en `storage/logs/laravel.log`
2. Verifica que XAMPP esté corriendo
3. Asegúrate de que todos los pasos previos se completaron
4. Revisa la documentación de Laravel: https://laravel.com/docs

## ✅ Checklist de Verificación

- [ ] XAMPP instalado y corriendo (Apache + MySQL)
- [ ] Base de datos `mascotas_felices` creada
- [ ] Archivo `.env` configurado
- [ ] Composer install ejecutado
- [ ] php artisan key:generate ejecutado
- [ ] php artisan migrate ejecutado
- [ ] php artisan db:seed ejecutado
- [ ] php artisan storage:link ejecutado
- [ ] php artisan serve ejecutado
- [ ] Navegador abierto en http://127.0.0.1:8000
- [ ] Login exitoso con credenciales de prueba

---

**¡Listo!** Tu sistema de Mascotas Felices debería estar funcionando correctamente. 🎉

Si todo está correcto, deberías poder:
- ✅ Iniciar sesión
- ✅ Ver el dashboard
- ✅ Gestionar productos, clientes, proveedores
- ✅ Realizar ventas
- ✅ Crear pedidos
- ✅ Ver reportes
- ✅ Usar la API REST
