<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\User;
use Carbon\Carbon;

class VentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        $usuarios = User::all();

        if ($clientes->isEmpty() || $productos->isEmpty() || $usuarios->isEmpty()) {
            $this->command->error('No hay clientes, productos o usuarios. Ejecuta primero los seeders principales.');
            return;
        }

        $this->command->info('Creando ventas de prueba...');

        $metodosFormato = ['efectivo', 'tarjeta', 'transferencia', 'yape', 'plin'];
        $tiposVenta = ['tienda', 'online'];
        $tiposComprobante = ['boleta', 'factura', 'ticket'];
        
        // Crear 50 ventas distribuidas en los últimos 3 meses
        for ($i = 0; $i < 50; $i++) {
            // Fecha aleatoria en los últimos 90 días
            $fechaVenta = Carbon::now()->subDays(rand(0, 90));
            
            // Determinar estado (más completadas)
            if ($i < 47) {
                $estado = 'completada';
            } else {
                $estado = 'anulada';
            }

            // Cliente aleatorio (80% con cliente, 20% sin cliente)
            $clienteId = (rand(1, 100) <= 80) ? $clientes->random()->id : null;
            
            // Usuario aleatorio
            $usuario = $usuarios->random();
            
            // Método de pago aleatorio
            $metodoPago = $metodosFormato[array_rand($metodosFormato)];
            
            // Tipo de venta y comprobante
            $tipoVenta = $tiposVenta[array_rand($tiposVenta)];
            $tipoComprobante = $tiposComprobante[array_rand($tiposComprobante)];
            
            // Generar número de venta
            $numeroVenta = 'V-' . date('Y', $fechaVenta->timestamp) . date('m', $fechaVenta->timestamp) . '-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT);
            
            // Número de comprobante
            $numeroComprobante = null;
            if ($tipoComprobante === 'boleta') {
                $numeroComprobante = 'B001-' . str_pad(rand(1, 9999), 8, '0', STR_PAD_LEFT);
            } elseif ($tipoComprobante === 'factura') {
                $numeroComprobante = 'F001-' . str_pad(rand(1, 9999), 8, '0', STR_PAD_LEFT);
            }

            // Crear la venta
            $venta = Venta::create([
                'numero_venta' => $numeroVenta,
                'cliente_id' => $clienteId,
                'usuario_id' => $usuario->id,
                'fecha_venta' => $fechaVenta,
                'tipo_venta' => $tipoVenta,
                'tipo_comprobante' => $tipoComprobante,
                'numero_comprobante' => $numeroComprobante,
                'metodo_pago' => $metodoPago,
                'estado' => $estado,
                'subtotal' => 0,
                'descuento' => 0,
                'impuesto' => 0,
                'total' => 0,
                'notas' => $this->getNotasVenta($estado, $metodoPago),
            ]);

            // Agregar entre 1 y 5 productos a la venta
            $cantidadProductos = rand(1, 5);
            $subtotalVenta = 0;

            for ($j = 0; $j < $cantidadProductos; $j++) {
                $producto = $productos->random();
                $cantidad = rand(1, 5);
                $precioUnitario = $producto->precio_venta;
                
                // Aplicar descuento aleatorio en algunos productos (20% de probabilidad)
                $descuentoProducto = 0;
                if (rand(1, 100) <= 20) {
                    $descuentoProducto = round($precioUnitario * rand(5, 15) / 100, 2);
                }
                
                $precioFinal = $precioUnitario - $descuentoProducto;
                $subtotal = $cantidad * $precioFinal;

                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'descuento' => $descuentoProducto * $cantidad,
                    'subtotal' => $subtotal,
                ]);

                $subtotalVenta += $subtotal;
            }

            // Aplicar descuento general (10% de probabilidad)
            $descuentoGeneral = 0;
            if (rand(1, 100) <= 10) {
                $descuentoGeneral = round($subtotalVenta * rand(5, 10) / 100, 2);
            }

            // Calcular impuesto (18% IGV)
            $impuesto = round(($subtotalVenta - $descuentoGeneral) * 0.18, 2);
            $totalVenta = $subtotalVenta - $descuentoGeneral + $impuesto;

            // Actualizar totales de la venta
            $venta->update([
                'subtotal' => $subtotalVenta,
                'descuento' => $descuentoGeneral,
                'impuesto' => $impuesto,
                'total' => $totalVenta,
            ]);

            // Actualizar stock si la venta está completada
            if ($estado === 'completada') {
                foreach ($venta->detalles as $detalle) {
                    $producto = Producto::find($detalle->producto_id);
                    if ($producto) {
                        $producto->stock_actual = max(0, $producto->stock_actual - $detalle->cantidad);
                        $producto->save();
                    }
                }
                
                // Actualizar puntos del cliente si existe
                if ($clienteId) {
                    $cliente = Cliente::find($clienteId);
                    if ($cliente) {
                        $puntosGanados = floor($totalVenta / 10); // 1 punto por cada S/. 10
                        $cliente->puntos_fidelidad += $puntosGanados;
                        $cliente->save();
                    }
                }
            }

            $this->command->info("Venta #{$venta->id} ({$numeroVenta}) creada: {$estado} - S/. {$totalVenta}");
        }

        $this->command->info('✅ Ventas de prueba creadas exitosamente!');
        $this->command->info('Total ventas: ' . Venta::count());
        $this->command->info('Completadas: ' . Venta::where('estado', 'completada')->count());
        $this->command->info('Anuladas: ' . Venta::where('estado', 'anulada')->count());
        $this->command->info('Monto total vendido: S/. ' . number_format(Venta::where('estado', 'completada')->sum('total'), 2));
    }

    /**
     * Generar notas para la venta según el estado
     */
    private function getNotasVenta($estado, $metodoPago): ?string
    {
        $notas = [
            'completada' => [
                'Venta realizada sin inconvenientes',
                'Cliente satisfecho',
                'Entrega inmediata',
                null,
                null,
            ],
            'anulada' => [
                'Anulada por solicitud del cliente',
                'Producto sin stock',
                'Error en el registro',
            ],
        ];

        $notasEstado = $notas[$estado] ?? [null];
        return $notasEstado[array_rand($notasEstado)];
    }
}
