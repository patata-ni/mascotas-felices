# Documentación API - Mascotas Felices

## Información General

- **Base URL**: `http://localhost:8000/api`
- **Autenticación**: Bearer Token (Laravel Sanctum)
- **Content-Type**: `application/json`
- **Accept**: `application/json`

## Autenticación

### 1. Login

Obtiene un token de acceso para autenticación.

**Endpoint**: `POST /login`

**Body**:
```json
{
  "email": "admin@mascotasfelices.com",
  "password": "password"
}
```

**Respuesta Exitosa** (200):
```json
{
  "user": {
    "id": 1,
    "name": "Administrador",
    "email": "admin@mascotasfelices.com",
    "role": "administrador",
    "telefono": "987654321",
    "activo": true
  },
  "token": "1|laravel_sanctum_xxxxxxxxxxxxxxxxxxxxxxxxx"
}
```

### 2. Registro

Crea una nueva cuenta de usuario.

**Endpoint**: `POST /register`

**Body**:
```json
{
  "name": "Nuevo Usuario",
  "email": "nuevo@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "telefono": "987654321"
}
```

**Respuesta Exitosa** (201):
```json
{
  "user": {...},
  "token": "2|laravel_sanctum_xxxxxxxxxxxxxxxxxxxxxxxxx"
}
```

### 3. Logout

Revoca el token de acceso actual.

**Endpoint**: `POST /logout`

**Headers**:
```
Authorization: Bearer {token}
```

**Respuesta Exitosa** (200):
```json
{
  "message": "Sesión cerrada exitosamente"
}
```

---

## Productos

### 1. Listar Productos

**Endpoint**: `GET /productos`

**Headers**:
```
Authorization: Bearer {token}
```

**Query Parameters** (opcionales):
- `buscar`: Buscar por nombre o código
- `categoria_id`: Filtrar por categoría
- `page`: Número de página (paginación)

**Ejemplo**: `/productos?buscar=comida&categoria_id=1`

**Respuesta Exitosa** (200):
```json
{
  "data": [
    {
      "id": 1,
      "codigo": "ALM001",
      "nombre": "Comida Premium para Perros Adultos 15kg",
      "descripcion": "Alimento balanceado para perros adultos",
      "categoria_id": 1,
      "proveedor_id": 1,
      "precio_compra": "80.00",
      "precio_venta": "120.00",
      "stock_actual": 50,
      "stock_minimo": 10,
      "stock_maximo": 100,
      "unidad_medida": "bolsa",
      "imagen": null,
      "activo": true,
      "categoria": {
        "id": 1,
        "nombre": "Alimentos"
      },
      "proveedor": {
        "id": 1,
        "nombre": "Pet Food Supply SAC"
      }
    }
  ],
  "links": {...},
  "meta": {...}
}
```

### 2. Ver Producto

**Endpoint**: `GET /productos/{id}`

**Respuesta Exitosa** (200):
```json
{
  "id": 1,
  "codigo": "ALM001",
  "nombre": "Comida Premium para Perros Adultos 15kg",
  ...
}
```

### 3. Productos con Stock Bajo

**Endpoint**: `GET /productos/stock/bajo`

**Respuesta Exitosa** (200):
```json
[
  {
    "id": 6,
    "codigo": "HIG002",
    "nombre": "Arena para Gatos 5kg",
    "stock_actual": 5,
    "stock_minimo": 10,
    "categoria": {
      "nombre": "Higiene"
    }
  }
]
```

---

## Clientes

### 1. Listar Clientes

**Endpoint**: `GET /clientes`

**Query Parameters** (opcionales):
- `buscar`: Buscar por nombre o documento
- `page`: Número de página

**Respuesta Exitosa** (200):
```json
{
  "data": [
    {
      "id": 1,
      "nombre": "Juan Carlos Rodríguez",
      "documento": "12345678",
      "tipo_documento": "DNI",
      "telefono": "987654321",
      "email": "juan.rodriguez@email.com",
      "direccion": "Av. Los Pinos 123, Lima",
      "fecha_nacimiento": "1985-05-15",
      "puntos_fidelidad": 0,
      "total_compras": "0.00",
      "activo": true
    }
  ]
}
```

### 2. Ver Cliente

**Endpoint**: `GET /clientes/{id}`

### 3. Crear Cliente

**Endpoint**: `POST /clientes`

**Body**:
```json
{
  "nombre": "María García",
  "documento": "87654321",
  "tipo_documento": "DNI",
  "telefono": "987654322",
  "email": "maria@email.com",
  "direccion": "Jr. Las Flores 456",
  "fecha_nacimiento": "1990-08-20"
}
```

**Respuesta Exitosa** (201):
```json
{
  "id": 4,
  "nombre": "María García",
  ...
}
```

### 4. Historial de Compras

**Endpoint**: `GET /clientes/{id}/historial`

**Respuesta Exitosa** (200):
```json
{
  "data": [
    {
      "id": 1,
      "numero_venta": "VEN-20251103-0001",
      "fecha_venta": "2025-11-03",
      "total": "135.00",
      "detalles": [
        {
          "producto": {
            "nombre": "Comida Premium para Perros"
          },
          "cantidad": 1,
          "precio_unitario": "120.00"
        }
      ]
    }
  ]
}
```

---

## Ventas

### 1. Listar Ventas

**Endpoint**: `GET /ventas`

**Respuesta Exitosa** (200):
```json
{
  "data": [
    {
      "id": 1,
      "numero_venta": "VEN-20251103-0001",
      "cliente_id": 1,
      "usuario_id": 2,
      "fecha_venta": "2025-11-03",
      "tipo_venta": "tienda",
      "tipo_comprobante": "boleta",
      "metodo_pago": "efectivo",
      "subtotal": "120.00",
      "descuento": "0.00",
      "impuesto": "21.60",
      "total": "141.60",
      "puntos_otorgados": 14,
      "estado": "completada",
      "cliente": {
        "nombre": "Juan Carlos Rodríguez"
      },
      "usuario": {
        "name": "Vendedor Principal"
      }
    }
  ]
}
```

### 2. Ver Venta

**Endpoint**: `GET /ventas/{id}`

### 3. Crear Venta (Punto de Venta)

**Endpoint**: `POST /ventas`

**Body**:
```json
{
  "cliente_id": 1,
  "tipo_venta": "tienda",
  "tipo_comprobante": "boleta",
  "metodo_pago": "efectivo",
  "descuento": 0,
  "productos": [
    {
      "producto_id": 1,
      "cantidad": 1,
      "precio_unitario": 120.00,
      "descuento": 0
    },
    {
      "producto_id": 3,
      "cantidad": 2,
      "precio_unitario": 15.00,
      "descuento": 0
    }
  ]
}
```

**Respuesta Exitosa** (201):
```json
{
  "id": 2,
  "numero_venta": "VEN-20251103-0002",
  "total": "177.00",
  "detalles": [...]
}
```

**Errores Comunes**:
- `400`: Stock insuficiente
- `422`: Validación fallida

### 4. Anular Venta

**Endpoint**: `POST /ventas/{id}/anular`

**Respuesta Exitosa** (200):
```json
{
  "message": "Venta anulada exitosamente"
}
```

---

## Pedidos

### 1. Listar Pedidos

**Endpoint**: `GET /pedidos`

**Respuesta Exitosa** (200):
```json
{
  "data": [
    {
      "id": 1,
      "numero_pedido": "PED-20251103-0001",
      "proveedor_id": 1,
      "usuario_id": 3,
      "fecha_pedido": "2025-11-03",
      "fecha_entrega_estimada": "2025-11-10",
      "estado": "pendiente",
      "total": "1500.00",
      "proveedor": {
        "nombre": "Pet Food Supply SAC"
      }
    }
  ]
}
```

### 2. Ver Pedido

**Endpoint**: `GET /pedidos/{id}`

### 3. Recibir Pedido

Marca el pedido como recibido y actualiza el stock de productos.

**Endpoint**: `POST /pedidos/{id}/recibir`

**Respuesta Exitosa** (200):
```json
{
  "message": "Pedido recibido exitosamente. Stock actualizado."
}
```

### 4. Cancelar Pedido

**Endpoint**: `POST /pedidos/{id}/cancelar`

**Respuesta Exitosa** (200):
```json
{
  "message": "Pedido cancelado exitosamente"
}
```

---

## Estadísticas

### Dashboard Stats

Obtiene estadísticas generales para el dashboard.

**Endpoint**: `GET /dashboard/stats`

**Respuesta Exitosa** (200):
```json
{
  "ventas_hoy": "500.00",
  "cantidad_ventas_hoy": 5,
  "productos_stock_bajo": 3,
  "clientes_activos": 15,
  "pedidos_pendientes": 2
}
```

---

## Códigos de Estado HTTP

- `200 OK`: Solicitud exitosa
- `201 Created`: Recurso creado exitosamente
- `400 Bad Request`: Error en la solicitud
- `401 Unauthorized`: No autenticado
- `403 Forbidden`: No autorizado (sin permisos)
- `404 Not Found`: Recurso no encontrado
- `422 Unprocessable Entity`: Error de validación
- `500 Internal Server Error`: Error del servidor

---

## Ejemplo de Uso en JavaScript

```javascript
// Login
const login = async () => {
  const response = await fetch('http://localhost:8000/api/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify({
      email: 'admin@mascotasfelices.com',
      password: 'password'
    })
  });
  
  const data = await response.json();
  const token = data.token;
  
  // Guardar token
  localStorage.setItem('token', token);
  return token;
};

// Obtener productos
const getProductos = async () => {
  const token = localStorage.getItem('token');
  
  const response = await fetch('http://localhost:8000/api/productos', {
    method: 'GET',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json'
    }
  });
  
  const data = await response.json();
  return data.data;
};

// Crear venta
const crearVenta = async (ventaData) => {
  const token = localStorage.getItem('token');
  
  const response = await fetch('http://localhost:8000/api/ventas', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify(ventaData)
  });
  
  const data = await response.json();
  return data;
};
```

---

## Notas Importantes

1. **Autenticación**: Todos los endpoints (excepto login y register) requieren token de autenticación
2. **Permisos**: Algunos endpoints están restringidos por roles
3. **Paginación**: Los listados están paginados (20 items por página)
4. **Validación**: Todos los datos son validados antes de procesarse
5. **Transacciones**: Las ventas y pedidos usan transacciones de BD para garantizar integridad
6. **Stock**: El stock se actualiza automáticamente con ventas y pedidos recibidos
7. **Puntos**: Los puntos de fidelidad se calculan automáticamente (1 punto por cada 10 soles)

---

**Versión**: 1.0  
**Última actualización**: Noviembre 2025
