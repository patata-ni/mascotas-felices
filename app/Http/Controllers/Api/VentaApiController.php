<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaApiController extends Controller
{
    public function index(Request $request)
    {
        $ventas = Venta::with(['cliente', 'usuario'])
            ->orderBy('fecha_venta', 'desc')
            ->paginate(20);

        return response()->json($ventas);
    }

    public function show($id)
    {
        $venta = Venta::with(['cliente', 'usuario', 'detalles.producto'])->findOrFail($id);
        return response()->json($venta);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'tipo_venta' => 'required|in:tienda,online',
            'tipo_comprobante' => 'required|in:boleta,factura,ticket',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,yape,plin',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $venta = new Venta();
            $venta->numero_venta = Venta::generarNumeroVenta();
            $venta->cliente_id = $request->cliente_id;
            $venta->usuario_id = auth()->id();
            $venta->fecha_venta = now();
            $venta->tipo_venta = $request->tipo_venta;
            $venta->tipo_comprobante = $request->tipo_comprobante;
            $venta->metodo_pago = $request->metodo_pago;
            $venta->descuento = $request->descuento ?? 0;
            $venta->estado = 'completada';
            $venta->save();

            foreach ($request->productos as $prod) {
                $producto = Producto::find($prod['producto_id']);

                if ($producto->stock_actual < $prod['cantidad']) {
                    throw new \Exception("Stock insuficiente para: {$producto->nombre}");
                }

                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $prod['producto_id'],
                    'cantidad' => $prod['cantidad'],
                    'precio_unitario' => $prod['precio_unitario'],
                    'descuento' => $prod['descuento'] ?? 0,
                ]);
            }

            $venta->calcularTotal();
            $venta->actualizarStock();
            $venta->actualizarPuntosCliente();

            DB::commit();

            return response()->json($venta->load('detalles.producto'), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function anular($id)
    {
        $venta = Venta::findOrFail($id);

        if ($venta->estado == 'anulada') {
            return response()->json(['error' => 'La venta ya está anulada'], 400);
        }

        DB::beginTransaction();

        try {
            foreach ($venta->detalles as $detalle) {
                $producto = $detalle->producto;
                $producto->stock_actual += $detalle->cantidad;
                $producto->save();
            }

            if ($venta->cliente_id) {
                $cliente = $venta->cliente;
                $cliente->puntos_fidelidad -= $venta->puntos_otorgados;
                $cliente->total_compras -= $venta->total;
                $cliente->save();
            }

            $venta->estado = 'anulada';
            $venta->save();

            DB::commit();

            return response()->json(['message' => 'Venta anulada exitosamente']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
