<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoDetalle;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuarios
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@mascotasfelices.com',
            'password' => Hash::make('password'),
            'role' => 'administrador',
            'telefono' => '987654321',
            'activo' => true,
        ]);

        User::create([
            'name' => 'Vendedor Principal',
            'email' => 'vendedor@mascotasfelices.com',
            'password' => Hash::make('password'),
            'role' => 'vendedor',
            'telefono' => '987654322',
            'activo' => true,
        ]);

        User::create([
            'name' => 'Encargado Inventario',
            'email' => 'inventario@mascotasfelices.com',
            'password' => Hash::make('password'),
            'role' => 'inventario',
            'telefono' => '987654323',
            'activo' => true,
        ]);

        // Crear categorías
        $categorias = [
            ['nombre' => 'Alimentos', 'descripcion' => 'Alimentos para mascotas'],
            ['nombre' => 'Juguetes', 'descripcion' => 'Juguetes y accesorios de entretenimiento'],
            ['nombre' => 'Higiene', 'descripcion' => 'Productos de higiene y cuidado'],
            ['nombre' => 'Accesorios', 'descripcion' => 'Collares, correas, ropa, etc.'],
            ['nombre' => 'Medicamentos', 'descripcion' => 'Medicamentos y suplementos'],
            ['nombre' => 'Camas y Casas', 'descripcion' => 'Camas, casas y refugios'],
        ];

        foreach ($categorias as $cat) {
            Categoria::create($cat);
        }

        // Crear proveedores
        $proveedores = [
            [
                'nombre' => 'Pet Food Supply SAC',
                'ruc' => '20123456789',
                'telefono' => '014567890',
                'email' => 'ventas@petfood.com',
                'direccion' => 'Av. Principal 123, Lima',
                'contacto_nombre' => 'Juan Pérez',
                'contacto_telefono' => '987654321',
                'evaluacion' => 4.5,
            ],
            [
                'nombre' => 'Distribuidora Pet Care EIRL',
                'ruc' => '20987654321',
                'telefono' => '014567891',
                'email' => 'contacto@petcare.com',
                'direccion' => 'Jr. Comercio 456, Lima',
                'contacto_nombre' => 'María García',
                'contacto_telefono' => '987654322',
                'evaluacion' => 4.8,
            ],
            [
                'nombre' => 'Toy Pets Import SA',
                'ruc' => '20456789123',
                'telefono' => '014567892',
                'email' => 'info@toypets.com',
                'direccion' => 'Av. Industrial 789, Lima',
                'contacto_nombre' => 'Carlos López',
                'contacto_telefono' => '987654323',
                'evaluacion' => 4.2,
            ],
        ];

        foreach ($proveedores as $prov) {
            Proveedor::create($prov);
        }

        // Crear productos
        $productos = [
            // Alimentos
            [
                'codigo' => 'ALM001',
                'nombre' => 'Comida Premium para Perros Adultos 15kg',
                'descripcion' => 'Alimento balanceado para perros adultos',
                'categoria_id' => 1,
                'proveedor_id' => 1,
                'precio_compra' => 80.00,
                'precio_venta' => 120.00,
                'stock_actual' => 50,
                'stock_minimo' => 10,
                'stock_maximo' => 100,
                'unidad_medida' => 'bolsa',
            ],
            [
                'codigo' => 'ALM002',
                'nombre' => 'Comida para Gatos 10kg',
                'descripcion' => 'Alimento completo para gatos',
                'categoria_id' => 1,
                'proveedor_id' => 1,
                'precio_compra' => 60.00,
                'precio_venta' => 90.00,
                'stock_actual' => 40,
                'stock_minimo' => 10,
                'stock_maximo' => 80,
                'unidad_medida' => 'bolsa',
            ],
            // Juguetes
            [
                'codigo' => 'JUG001',
                'nombre' => 'Pelota de Goma para Perros',
                'descripcion' => 'Pelota resistente para perros grandes',
                'categoria_id' => 2,
                'proveedor_id' => 3,
                'precio_compra' => 8.00,
                'precio_venta' => 15.00,
                'stock_actual' => 100,
                'stock_minimo' => 20,
                'stock_maximo' => 200,
                'unidad_medida' => 'unidad',
            ],
            [
                'codigo' => 'JUG002',
                'nombre' => 'Ratón de Juguete para Gatos',
                'descripcion' => 'Juguete interactivo para gatos',
                'categoria_id' => 2,
                'proveedor_id' => 3,
                'precio_compra' => 5.00,
                'precio_venta' => 10.00,
                'stock_actual' => 80,
                'stock_minimo' => 15,
                'stock_maximo' => 150,
                'unidad_medida' => 'unidad',
            ],
            // Higiene
            [
                'codigo' => 'HIG001',
                'nombre' => 'Shampoo para Perros 500ml',
                'descripcion' => 'Shampoo antipulgas y garrapatas',
                'categoria_id' => 3,
                'proveedor_id' => 2,
                'precio_compra' => 15.00,
                'precio_venta' => 25.00,
                'stock_actual' => 60,
                'stock_minimo' => 15,
                'stock_maximo' => 120,
                'unidad_medida' => 'botella',
            ],
            [
                'codigo' => 'HIG002',
                'nombre' => 'Arena para Gatos 5kg',
                'descripcion' => 'Arena sanitaria aglomerante',
                'categoria_id' => 3,
                'proveedor_id' => 2,
                'precio_compra' => 12.00,
                'precio_venta' => 20.00,
                'stock_actual' => 5,
                'stock_minimo' => 10,
                'stock_maximo' => 100,
                'unidad_medida' => 'bolsa',
            ],
            // Accesorios
            [
                'codigo' => 'ACC001',
                'nombre' => 'Collar para Perros Medianos',
                'descripcion' => 'Collar ajustable de nylon',
                'categoria_id' => 4,
                'proveedor_id' => 3,
                'precio_compra' => 10.00,
                'precio_venta' => 18.00,
                'stock_actual' => 45,
                'stock_minimo' => 10,
                'stock_maximo' => 80,
                'unidad_medida' => 'unidad',
            ],
            [
                'codigo' => 'ACC002',
                'nombre' => 'Plato de Acero Inoxidable',
                'descripcion' => 'Plato para comida y agua',
                'categoria_id' => 4,
                'proveedor_id' => 3,
                'precio_compra' => 15.00,
                'precio_venta' => 25.00,
                'stock_actual' => 35,
                'stock_minimo' => 10,
                'stock_maximo' => 60,
                'unidad_medida' => 'unidad',
            ],
        ];

        foreach ($productos as $prod) {
            Producto::create($prod);
        }

        // Crear clientes
        $clientes = [
            [
                'nombre' => 'Juan Carlos Rodríguez',
                'documento' => '12345678',
                'tipo_documento' => 'DNI',
                'telefono' => '987654321',
                'email' => 'juan.rodriguez@email.com',
                'direccion' => 'Av. Los Pinos 123, Lima',
                'fecha_nacimiento' => '1985-05-15',
                'puntos_fidelidad' => 0,
                'total_compras' => 0,
            ],
            [
                'nombre' => 'María Elena García',
                'documento' => '87654321',
                'tipo_documento' => 'DNI',
                'telefono' => '987654322',
                'email' => 'maria.garcia@email.com',
                'direccion' => 'Jr. Las Flores 456, Lima',
                'fecha_nacimiento' => '1990-08-20',
                'puntos_fidelidad' => 0,
                'total_compras' => 0,
            ],
            [
                'nombre' => 'Pet Shop Amigos SAC',
                'documento' => '20123456780',
                'tipo_documento' => 'RUC',
                'telefono' => '014567890',
                'email' => 'ventas@petshopamigos.com',
                'direccion' => 'Av. Principal 789, Lima',
                'puntos_fidelidad' => 0,
                'total_compras' => 0,
            ],
        ];

        foreach ($clientes as $cli) {
            Cliente::create($cli);
        }

        // Crear pedidos de prueba
        $fechaActual = date('Ymd');
        $pedidos = [
            [
                'numero_pedido' => "PED-{$fechaActual}-0001",
                'proveedor_id' => 1,
                'usuario_id' => 3,
                'fecha_pedido' => now()->subDays(5),
                'fecha_entrega_estimada' => now()->addDays(2),
                'estado' => 'pendiente',
                'notas' => 'Pedido urgente de alimentos',
            ],
            [
                'numero_pedido' => "PED-{$fechaActual}-0002",
                'proveedor_id' => 2,
                'usuario_id' => 3,
                'fecha_pedido' => now()->subDays(10),
                'fecha_entrega_estimada' => now()->subDays(3),
                'fecha_entrega_real' => now()->subDays(4),
                'estado' => 'recibido',
                'notas' => 'Pedido de productos de higiene',
            ],
            [
                'numero_pedido' => "PED-{$fechaActual}-0003",
                'proveedor_id' => 3,
                'usuario_id' => 1,
                'fecha_pedido' => now()->subDays(3),
                'fecha_entrega_estimada' => now()->addDays(5),
                'estado' => 'confirmado',
                'notas' => 'Juguetes y accesorios',
            ],
        ];

        foreach ($pedidos as $ped) {
            $pedido = Pedido::create($ped);
            
            // Agregar detalles al pedido según el proveedor
            if ($pedido->proveedor_id == 1) {
                // Proveedor de alimentos
                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => 1,
                    'cantidad' => 20,
                    'precio_unitario' => 80.00,
                ]);
                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => 2,
                    'cantidad' => 15,
                    'precio_unitario' => 60.00,
                ]);
            } elseif ($pedido->proveedor_id == 2) {
                // Proveedor de higiene
                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => 5,
                    'cantidad' => 30,
                    'precio_unitario' => 15.00,
                ]);
                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => 6,
                    'cantidad' => 50,
                    'precio_unitario' => 12.00,
                ]);
            } else {
                // Proveedor de juguetes
                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => 3,
                    'cantidad' => 40,
                    'precio_unitario' => 8.00,
                ]);
                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => 4,
                    'cantidad' => 30,
                    'precio_unitario' => 5.00,
                ]);
            }
            
            // Refrescar la relación y calcular total
            $pedido->load('detalles');
            $pedido->calcularTotal();
        }

        $this->command->info('Base de datos poblada exitosamente!');
        $this->command->info('Usuario admin: admin@mascotasfelices.com');
        $this->command->info('Usuario vendedor: vendedor@mascotasfelices.com');
        $this->command->info('Usuario inventario: inventario@mascotasfelices.com');
        $this->command->info('Contraseña para todos: password');
    }
}
