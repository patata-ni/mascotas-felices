# 🔐 Roles y Permisos - Mascotas Felices

## Resumen de Acceso por Rol

### 👨‍💼 Administrador (acceso total)
**Email:** `admin@mascotasfelices.com` | **Password:** `password`

#### Módulos con Acceso Completo:
- ✅ **Dashboard** - Vista completa con todas las métricas
- ✅ **Productos** - Listado, Crear, Editar, Eliminar, Stock Bajo
- ✅ **Categorías** - CRUD completo
- ✅ **Clientes** - CRUD completo + Historial de compras
- ✅ **Proveedores** - CRUD completo
- ✅ **Pedidos** - CRUD completo + Recibir/Cancelar
- ✅ **Ventas** - Punto de Venta, Historial, Anular ventas
- ✅ **Reportes** - Acceso a todos los reportes:
  - Ventas
  - Inventario
  - Productos más vendidos
  - Clientes
  - Pedidos
  - Financiero

---

### 📦 Encargado de Inventario
**Email:** `inventario@mascotasfelices.com` | **Password:** `password`

#### Módulos con Acceso:
- ✅ **Dashboard** - Vista simplificada (métricas de inventario)
- ✅ **Productos** - CRUD completo + Stock Bajo
- ✅ **Categorías** - CRUD completo
- ✅ **Proveedores** - CRUD completo
- ✅ **Pedidos** - CRUD completo + Recibir/Cancelar pedidos

#### Módulos SIN Acceso:
- ❌ **Clientes** - No puede ver ni gestionar clientes
- ❌ **Ventas** - No tiene acceso al punto de venta ni historial
- ❌ **Reportes** - No puede ver reportes financieros

**Funciones principales:**
- Gestionar el catálogo de productos
- Controlar niveles de inventario
- Gestionar proveedores
- Crear y recibir pedidos de compra
- Monitorear productos con stock bajo

---

### 💰 Vendedor
**Email:** `vendedor@mascotasfelices.com` | **Password:** `password`

#### Módulos con Acceso:
- ✅ **Dashboard** - Vista simplificada (métricas de ventas)
- ✅ **Clientes** - CRUD completo + Historial de compras
- ✅ **Ventas** - Punto de Venta + Historial de ventas

#### Módulos SIN Acceso:
- ❌ **Productos** - No puede crear ni editar productos (solo consulta en punto de venta)
- ❌ **Categorías** - No puede gestionar categorías
- ❌ **Proveedores** - No puede ver ni gestionar proveedores
- ❌ **Pedidos** - No puede crear ni gestionar pedidos de compra
- ❌ **Reportes** - No puede ver reportes financieros
- ❌ **Anular ventas** - No puede anular ventas (solo administrador)

**Funciones principales:**
- Realizar ventas en el punto de venta
- Gestionar información de clientes
- Consultar historial de ventas y clientes
- Ver productos disponibles (solo lectura en POS)

---

## Matriz de Permisos

| Módulo | Administrador | Inventario | Vendedor |
|--------|--------------|------------|----------|
| Dashboard | ✅ Completo | ✅ Simplificado | ✅ Simplificado |
| Productos | ✅ CRUD | ✅ CRUD | ❌ |
| Categorías | ✅ CRUD | ✅ CRUD | ❌ |
| Clientes | ✅ CRUD | ❌ | ✅ CRUD |
| Proveedores | ✅ CRUD | ✅ CRUD | ❌ |
| Pedidos | ✅ CRUD + Acciones | ✅ CRUD + Acciones | ❌ |
| Ventas | ✅ CRUD + Anular | ❌ | ✅ CRUD (sin Anular) |
| Reportes | ✅ Todos | ❌ | ❌ |

---

## Acciones Especiales por Rol

### Solo Administrador:
- ✅ Anular ventas
- ✅ Ver todos los reportes financieros
- ✅ Acceso completo sin restricciones

### Solo Inventario:
- ✅ Recibir pedidos de compra
- ✅ Cancelar pedidos pendientes
- ✅ Alertas de stock bajo

### Solo Vendedor:
- ✅ Procesar ventas
- ✅ Imprimir comprobantes
- ✅ Ver historial de ventas

---

## Implementación Técnica

### Middleware de Rutas (`routes/web.php`)
```php
// Solo Administrador
Route::middleware(['role:administrador'])->group(function () {
    // Reportes
});

// Administrador + Inventario
Route::middleware(['role:administrador,inventario'])->group(function () {
    // Productos, Categorías, Proveedores, Pedidos
});

// Administrador + Vendedor
Route::middleware(['role:administrador,vendedor'])->group(function () {
    // Clientes, Ventas
});
```

### Métodos de Verificación de Rol (Modelo User)
```php
// app/Models/User.php
public function esAdministrador(): bool
{
    return $this->role === 'administrador';
}

public function esVendedor(): bool
{
    return $this->role === 'vendedor';
}

public function esInventario(): bool
{
    return $this->role === 'inventario';
}
```

### Control de Sidebar (`resources/views/layouts/app.blade.php`)
```blade
@if(Auth::user()->esAdministrador() || Auth::user()->esInventario())
    <!-- Menú de Productos, Proveedores, Pedidos -->
@endif

@if(Auth::user()->esAdministrador() || Auth::user()->esVendedor())
    <!-- Menú de Clientes, Ventas -->
@endif

@if(Auth::user()->esAdministrador())
    <!-- Menú de Reportes -->
@endif
```

---

## Notas de Seguridad

1. **Protección de Rutas**: Todas las rutas están protegidas con middleware de autenticación y rol
2. **Validación en Backend**: El middleware `CheckRole` valida permisos antes de acceder a cualquier ruta
3. **UI Limpia**: Los usuarios solo ven las opciones a las que tienen acceso
4. **Redirección**: Intentos de acceso no autorizado redirigen al dashboard con mensaje de error

---

## Testing de Roles

Para probar cada rol:

1. **Cerrar sesión actual**
2. **Iniciar sesión con las credenciales correspondientes**
3. **Verificar que el sidebar muestra solo las opciones permitidas**
4. **Intentar acceder directamente a una URL no permitida** (debería redirigir)

---

**Última actualización:** 7 de noviembre de 2025
