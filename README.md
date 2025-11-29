# Sistema de Gestión para Mascotas Felices 🐾

Sistema integral de gestión para tienda de productos de mascotas, desarrollado en Laravel 10 con arquitectura multiplataforma (Web, Android, iOS).

## 📋 Descripción del Proyecto

Mascotas Felices es una tienda especializada en productos para mascotas que necesitaba modernizar sus operaciones. Este sistema reemplaza las planillas Excel y registros en papel con una solución completa que incluye:

- ✅ Gestión integral de inventario
- ✅ Control de clientes y programa de fidelidad
- ✅ Gestión de proveedores y pedidos
- ✅ Punto de venta con múltiples métodos de pago
- ✅ Sistema de reportes y estadísticas
- ✅ API REST para aplicaciones móviles
- ✅ Control de acceso por roles

## 🚀 Características Principales

### Módulos Implementados

1. **Gestión de Productos**
   - CRUD completo de productos
   - Control de inventario en tiempo real
   - Alertas de stock bajo
   - Categorización de productos
   - Gestión de imágenes

2. **Gestión de Clientes**
   - Registro de clientes
   - Historial de compras
   - Programa de fidelidad con puntos
   - Seguimiento de gastos totales

3. **Gestión de Proveedores**
   - Registro de proveedores
   - Sistema de evaluación
   - Historial de pedidos

4. **Gestión de Pedidos**
   - Creación de pedidos a proveedores
   - Seguimiento de estados
   - Recepción de mercancía
   - Actualización automática de inventario

5. **Módulo de Ventas (POS)**
   - Punto de venta intuitivo
   - Múltiples métodos de pago
   - Actualización automática de stock
   - Cálculo de puntos de fidelidad

6. **Reportes y Estadísticas**
   - Reporte de ventas
   - Reporte de inventario
   - Productos más vendidos
   - Estadísticas del dashboard

### Sistema de Roles

- **Administrador**: Acceso completo al sistema
- **Vendedor**: Acceso a ventas y consulta de clientes
- **Inventario**: Acceso a productos, proveedores y pedidos

## 📦 Instalación

### Requisitos Previos

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- XAMPP (en uso)

### Pasos de Instalación

1. **Configurar la base de datos en .env**
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mascotas_felices
   DB_USERNAME=root
   DB_PASSWORD=
   ```

2. **Ejecutar las migraciones**
   ```bash
   php artisan migrate
   ```

3. **Ejecutar los seeders (datos de prueba)**
   ```bash
   php artisan db:seed
   ```

4. **Crear el enlace simbólico para storage**
   ```bash
   php artisan storage:link
   ```

## 👥 Usuarios de Prueba

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | admin@mascotasfelices.com | password |
| Vendedor | vendedor@mascotasfelices.com | password |
| Inventario | inventario@mascotasfelices.com | password |

## 📱 API REST

### Autenticación

**Login**
```
POST /api/login
Body: {
  "email": "admin@mascotasfelices.com",
  "password": "password"
}
```

### Endpoints Principales

- `GET /api/productos` - Listar productos
- `GET /api/clientes` - Listar clientes
- `POST /api/ventas` - Crear venta
- `GET /api/dashboard/stats` - Estadísticas

**Nota**: Todos los endpoints requieren autenticación mediante token Bearer.

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
