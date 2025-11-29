<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoApiController extends Controller
{
    public function index(Request $request)
    {
        $pedidos = Pedido::with(['proveedor', 'usuario'])
            ->orderBy('fecha_pedido', 'desc')
            ->paginate(20);

        return response()->json($pedidos);
    }

    public function show($id)
    {
        $pedido = Pedido::with(['proveedor', 'usuario', 'detalles.producto'])->findOrFail($id);
        return response()->json($pedido);
    }

    public function recibir($id)
    {
        $pedido = Pedido::findOrFail($id);

        if ($pedido->estado == 'recibido') {
            return response()->json(['error' => 'El pedido ya fue recibido'], 400);
        }

        DB::beginTransaction();

        try {
            foreach ($pedido->detalles as $detalle) {
                $producto = $detalle->producto;
                $producto->stock_actual += $detalle->cantidad;
                $producto->save();
            }

            $pedido->estado = 'recibido';
            $pedido->fecha_entrega_real = now();
            $pedido->save();

            DB::commit();

            return response()->json(['message' => 'Pedido recibido exitosamente. Stock actualizado.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function cancelar($id)
    {
        $pedido = Pedido::findOrFail($id);

        if ($pedido->estado == 'recibido') {
            return response()->json(['error' => 'No se puede cancelar un pedido ya recibido'], 400);
        }

        if ($pedido->estado == 'cancelado') {
            return response()->json(['error' => 'El pedido ya está cancelado'], 400);
        }

        $pedido->estado = 'cancelado';
        $pedido->save();

        return response()->json(['message' => 'Pedido cancelado exitosamente']);
    }
}
