<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pedido::with(['proveedor', 'usuario']);

        // Búsqueda
        if ($request->has('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('numero_pedido', 'like', "%{$buscar}%")
                  ->orWhereHas('proveedor', function($q) use ($buscar) {
                      $q->where('nombre', 'like', "%{$buscar}%");
                  });
            });
        }

        // Filtro por estado
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        // Filtro por proveedor
        if ($request->has('proveedor_id') && $request->proveedor_id != '') {
            $query->where('proveedor_id', $request->proveedor_id);
        }

        $pedidos = $query->orderBy('fecha_pedido', 'desc')->paginate(15);
        $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();

        return view('pedidos.index', compact('pedidos', 'proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();
        $productos = Producto::where('activo', true)->with('categoria')->orderBy('nombre')->get();
        
        // Producto seleccionado desde stock bajo
        $productoSeleccionado = null;
        if ($request->has('producto_id')) {
            $productoSeleccionado = Producto::find($request->producto_id);
        }

        return view('pedidos.create', compact('proveedores', 'productos', 'productoSeleccionado'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_esperada' => 'nullable|date',
            'observaciones' => 'nullable',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Crear el pedido
            $pedido = new Pedido();
            $pedido->numero_pedido = Pedido::generarNumeroPedido();
            $pedido->proveedor_id = $request->proveedor_id;
            $pedido->usuario_id = auth()->id();
            $pedido->fecha_pedido = now();
            $pedido->fecha_entrega_estimada = $request->fecha_esperada;
            $pedido->estado = 'pendiente';
            $pedido->notas = $request->observaciones;
            $pedido->save();

            // Crear los detalles del pedido
            foreach ($request->detalles as $detalle) {
                PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                ]);
            }

            // Calcular totales
            $pedido->calcularTotal();

            DB::commit();

            // Si es petición AJAX, retornar JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pedido creado exitosamente',
                    'id' => $pedido->id
                ]);
            }

            return redirect()->route('pedidos.show', $pedido->id)
                ->with('success', 'Pedido creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Si es petición AJAX, retornar error JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el pedido: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->withInput()
                ->with('error', 'Error al crear el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        $pedido->load(['proveedor', 'usuario', 'detalles.producto']);
        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pedido $pedido)
    {
        if ($pedido->estado == 'recibido' || $pedido->estado == 'cancelado') {
            return redirect()->route('pedidos.show', $pedido->id)
                ->with('error', 'No se puede editar un pedido recibido o cancelado');
        }

        $proveedores = Proveedor::where('activo', true)->orderBy('nombre')->get();
        $productos = Producto::where('activo', true)->with('categoria')->orderBy('nombre')->get();
        $pedido->load('detalles.producto');

        return view('pedidos.edit', compact('pedido', 'proveedores', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        if ($pedido->estado == 'recibido' || $pedido->estado == 'cancelado') {
            return redirect()->route('pedidos.show', $pedido->id)
                ->with('error', 'No se puede editar un pedido recibido o cancelado');
        }

        $validated = $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_pedido' => 'required|date',
            'fecha_entrega_estimada' => 'nullable|date',
            'estado' => 'required|in:pendiente,confirmado,enviado,recibido,cancelado',
            'notas' => 'nullable',
        ]);

        $pedido->update($validated);

        return redirect()->route('pedidos.show', $pedido->id)
            ->with('success', 'Pedido actualizado exitosamente');
    }

    /**
     * Recibir pedido y actualizar stock
     */
    public function recibir(Pedido $pedido)
    {
        if ($pedido->estado == 'recibido') {
            return back()->with('error', 'El pedido ya fue recibido');
        }

        DB::beginTransaction();

        try {
            // Actualizar stock de productos
            foreach ($pedido->detalles as $detalle) {
                $producto = $detalle->producto;
                $producto->stock_actual += $detalle->cantidad;
                $producto->save();
            }

            // Actualizar estado del pedido
            $pedido->estado = 'recibido';
            $pedido->fecha_entrega_real = now();
            $pedido->save();

            DB::commit();

            return redirect()->route('pedidos.show', $pedido->id)
                ->with('success', 'Pedido recibido exitosamente. Stock actualizado.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al recibir el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Cancelar pedido
     */
    public function cancelar(Pedido $pedido)
    {
        if ($pedido->estado == 'recibido') {
            return back()->with('error', 'No se puede cancelar un pedido ya recibido');
        }

        if ($pedido->estado == 'cancelado') {
            return back()->with('error', 'El pedido ya está cancelado');
        }

        $pedido->estado = 'cancelado';
        $pedido->save();

        return redirect()->route('pedidos.show', $pedido->id)
            ->with('success', 'Pedido cancelado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pedido $pedido)
    {
        if ($pedido->estado == 'recibido') {
            return back()->with('error', 'No se puede eliminar un pedido ya recibido');
        }

        try {
            $pedido->delete();
            return redirect()->route('pedidos.index')
                ->with('success', 'Pedido eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el pedido');
        }
    }
}
