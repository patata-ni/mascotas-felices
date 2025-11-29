<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\User;
use Carbon\Carbon;

class PedidosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que existan proveedores y productos
        $proveedores = Proveedor::all();
        $productos = Producto::all();
        $usuarios = User::all();

        if ($proveedores->isEmpty() || $productos->isEmpty() || $usuarios->isEmpty()) {
            $this->command->error('No hay proveedores, productos o usuarios. Ejecuta primero los seeders principales.');
            return;
        }

        $this->command->info('Creando pedidos de prueba...');

        // Crear 15 pedidos con diferentes estados
        $estados = ['pendiente', 'confirmado', 'enviado', 'recibido', 'cancelado'];
        
        for ($i = 0; $i < 15; $i++) {
            // Seleccionar proveedor y usuario aleatorio
            $proveedor = $proveedores->random();
            $usuario = $usuarios->random();
            
            // Fecha aleatoria en los últimos 3 meses
            $fechaPedido = Carbon::now()->subDays(rand(0, 90));
            
            // Estado (más pedidos pendientes y confirmados)
            if ($i < 4) {
                $estado = 'pendiente';
            } elseif ($i < 8) {
                $estado = 'confirmado';
            } elseif ($i < 11) {
                $estado = 'enviado';
            } elseif ($i < 13) {
                $estado = 'recibido';
            } else {
                $estado = 'cancelado';
            }

            // Fecha de entrega esperada (7-15 días después del pedido)
            $fechaEntrega = (clone $fechaPedido)->addDays(rand(7, 15));
            
            // Si está recibido, poner fecha de entrega real
            $fechaEntregaReal = null;
            if ($estado === 'recibido') {
                $fechaEntregaReal = (clone $fechaEntrega)->addDays(rand(-2, 2));
            }

            // Número de pedido
            $numeroPedido = 'PED-' . date('Y', $fechaPedido->timestamp) . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

            // Crear el pedido
            $pedido = Pedido::create([
                'numero_pedido' => $numeroPedido,
                'proveedor_id' => $proveedor->id,
                'usuario_id' => $usuario->id,
                'fecha_pedido' => $fechaPedido,
                'fecha_entrega_estimada' => $fechaEntrega,
                'fecha_entrega_real' => $fechaEntregaReal,
                'estado' => $estado,
                'subtotal' => 0,
                'impuesto' => 0,
                'total' => 0, // Se calculará después
                'notas' => $this->getNotasPedido($estado, $i),
            ]);

            // Agregar entre 2 y 5 productos al pedido
            $cantidadProductos = rand(2, 5);
            $subtotalPedido = 0;

            for ($j = 0; $j < $cantidadProductos; $j++) {
                $producto = $productos->random();
                $cantidad = rand(5, 50);
                $precioUnitario = $producto->precio_compra;
                $subtotal = $cantidad * $precioUnitario;

                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotal,
                ]);

                $subtotalPedido += $subtotal;
            }

            // Calcular impuesto (16% IVA)
            $impuesto = $subtotalPedido * 0.16;
            $totalPedido = $subtotalPedido + $impuesto;

            // Actualizar el total del pedido
            $pedido->update([
                'subtotal' => $subtotalPedido,
                'impuesto' => $impuesto,
                'total' => $totalPedido
            ]);

            $this->command->info("Pedido #{$pedido->id} creado: {$estado} - \${$totalPedido}");
        }

        $this->command->info('✅ Pedidos de prueba creados exitosamente!');
        $this->command->info('Total: ' . Pedido::count() . ' pedidos');
        $this->command->info('Pendientes: ' . Pedido::where('estado', 'pendiente')->count());
        $this->command->info('En proceso: ' . Pedido::where('estado', 'en_proceso')->count());
        $this->command->info('Recibidos: ' . Pedido::where('estado', 'recibido')->count());
        $this->command->info('Cancelados: ' . Pedido::where('estado', 'cancelado')->count());
    }

    /**
     * Generar notas para el pedido según el estado
     */
    private function getNotasPedido($estado, $index): string
    {
        $notas = [
            'pendiente' => [
                'Pedido urgente - Stock bajo',
                'Pedido regular mensual',
                'Reposición de productos populares',
                'Pedido especial - Cliente VIP',
                'Stock de temporada',
            ],
            'confirmado' => [
                'Confirmado con proveedor',
                'En preparación',
                'Programado para envío',
                'Verificando disponibilidad',
            ],
            'enviado' => [
                'En tránsito',
                'Enviado por mensajería',
                'En camino - Llega pronto',
            ],
            'recibido' => [
                'Recibido completo - Sin novedad',
                'Recibido y verificado',
                'Todo en orden',
            ],
            'cancelado' => [
                'Cancelado por proveedor - Sin stock',
                'Cancelado - Productos discontinuados',
            ],
        ];

        $notasEstado = $notas[$estado] ?? ['Pedido #' . ($index + 1)];
        return $notasEstado[array_rand($notasEstado)];
    }
}
