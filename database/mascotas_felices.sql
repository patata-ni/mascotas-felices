-- ============================================
-- Base de Datos: Mascotas Felices
-- Sistema de Gestión para Tienda de Mascotas
-- ============================================

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS mascotas_felices
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Usar la base de datos
USE mascotas_felices;

-- Nota: Las tablas se crearán automáticamente mediante las migraciones de Laravel
-- Para crear las tablas, ejecuta en la terminal:
-- php artisan migrate

-- Para poblar con datos de prueba:
-- php artisan db:seed

-- ============================================
-- Estructura de las tablas (Referencia)
-- ============================================

-- users: Usuarios del sistema (administrador, vendedor, inventario)
-- categorias: Categorías de productos
-- proveedores: Proveedores de productos
-- productos: Productos para mascotas
-- clientes: Clientes de la tienda
-- pedidos: Pedidos a proveedores
-- pedido_detalles: Detalles de cada pedido
-- ventas: Ventas realizadas
-- venta_detalles: Detalles de cada venta

-- ============================================
-- Consultas útiles para verificación
-- ============================================

-- Ver todos los productos con stock bajo
-- SELECT p.codigo, p.nombre, p.stock_actual, p.stock_minimo, c.nombre as categoria
-- FROM productos p
-- INNER JOIN categorias c ON p.categoria_id = c.id
-- WHERE p.stock_actual <= p.stock_minimo;

-- Ver las ventas del día actual
-- SELECT v.numero_venta, c.nombre as cliente, v.total, v.fecha_venta
-- FROM ventas v
-- LEFT JOIN clientes c ON v.cliente_id = c.id
-- WHERE DATE(v.fecha_venta) = CURDATE()
-- ORDER BY v.created_at DESC;

-- Ver productos más vendidos
-- SELECT p.nombre, SUM(vd.cantidad) as total_vendido
-- FROM venta_detalles vd
-- INNER JOIN productos p ON vd.producto_id = p.id
-- INNER JOIN ventas v ON vd.venta_id = v.id
-- WHERE v.estado = 'completada'
-- GROUP BY p.id, p.nombre
-- ORDER BY total_vendido DESC
-- LIMIT 10;

-- Ver clientes con más compras
-- SELECT c.nombre, COUNT(v.id) as cantidad_compras, SUM(v.total) as total_gastado
-- FROM clientes c
-- INNER JOIN ventas v ON c.id = v.cliente_id
-- WHERE v.estado = 'completada'
-- GROUP BY c.id, c.nombre
-- ORDER BY total_gastado DESC;
