<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Venta::with(['cliente', 'usuario']);

        // Búsqueda
        if ($request->has('buscar') && $request->buscar != '') {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('numero_venta', 'like', "%{$buscar}%")
                  ->orWhere('numero_comprobante', 'like', "%{$buscar}%")
                  ->orWhereHas('cliente', function($q) use ($buscar) {
                      $q->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('apellidos', 'like', "%{$buscar}%");
                  });
            });
        }

        // Filtro por fecha desde
        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->whereDate('fecha_venta', '>=', $request->fecha_desde);
        }
        
        // Filtro por fecha hasta
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->whereDate('fecha_venta', '<=', $request->fecha_hasta);
        }

        // Filtro por cliente
        if ($request->has('cliente') && $request->cliente != '') {
            $cliente = $request->cliente;
            $query->whereHas('cliente', function($q) use ($cliente) {
                $q->where('nombre', 'like', "%{$cliente}%")
                  ->orWhere('apellidos', 'like', "%{$cliente}%")
                  ->orWhere('dni', 'like', "%{$cliente}%");
            });
        }

        // Filtro por método de pago
        if ($request->has('metodo_pago') && $request->metodo_pago != '') {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        // Filtro por estado
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        $ventas = $query->orderBy('fecha_venta', 'desc')->paginate(15);

        return view('ventas.index', compact('ventas'));
    }

    /**
     * Show the form for creating a new resource (Punto de Venta).
     */
    public function create()
    {
        $clientes = Cliente::where('activo', true)->orderBy('nombre')->get();
        $productos = Producto::where('activo', true)
            ->where('stock_actual', '>', 0)
            ->with('categoria')
            ->orderBy('nombre')
            ->get();
        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();

        return view('ventas.create', compact('clientes', 'productos', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Normalizar el nombre del array de productos/detalles
        $detallesKey = $request->has('detalles') ? 'detalles' : 'productos';
        
        $validated = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'tipo_venta' => 'nullable|in:tienda,online',
            'tipo_comprobante' => 'nullable|in:boleta,factura,ticket',
            'numero_comprobante' => 'nullable|max:50',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,yape,plin',
            'descuento' => 'nullable|numeric|min:0',
            'notas' => 'nullable',
            $detallesKey => 'required|array|min:1',
            $detallesKey.'.*.producto_id' => 'required|exists:productos,id',
            $detallesKey.'.*.cantidad' => 'required|integer|min:1',
            $detallesKey.'.*.precio_unitario' => 'required|numeric|min:0',
            $detallesKey.'.*.descuento' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Crear la venta
            $venta = new Venta();
            $venta->numero_venta = Venta::generarNumeroVenta();
            $venta->cliente_id = $request->cliente_id;
            $venta->usuario_id = auth()->id();
            $venta->fecha_venta = now();
            $venta->tipo_venta = $request->tipo_venta ?? 'tienda';
            $venta->tipo_comprobante = $request->tipo_comprobante ?? 'ticket';
            $venta->numero_comprobante = $request->numero_comprobante;
            $venta->metodo_pago = $request->metodo_pago;
            $venta->descuento = $request->descuento ?? 0;
            $venta->notas = $request->notas;
            $venta->estado = 'completada';
            $venta->save();

            // Crear los detalles de la venta
            $detalles = $request->input($detallesKey);
            foreach ($detalles as $detalle) {
                $producto = Producto::find($detalle['producto_id']);

                // Verificar stock
                if ($producto->stock_actual < $detalle['cantidad']) {
                    throw new \Exception("Stock insuficiente para el producto: {$producto->nombre}");
                }

                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'descuento' => $detalle['descuento'] ?? 0,
                ]);
            }

            // Calcular totales
            $venta->calcularTotal();

            // Actualizar stock
            $venta->actualizarStock();

            // Actualizar puntos del cliente
            $venta->actualizarPuntosCliente();

            DB::commit();

            // Si es petición AJAX, retornar JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Venta registrada exitosamente',
                    'id' => $venta->id
                ]);
            }

            return redirect()->route('ventas.show', $venta->id)
                ->with('success', 'Venta registrada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Si es petición AJAX, retornar error JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar la venta: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->withInput()
                ->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'usuario', 'detalles.producto']);
        return view('ventas.show', compact('venta'));
    }

    /**
     * Anular una venta
     */
    public function anular(Venta $venta)
    {
        if ($venta->estado == 'anulada') {
            return back()->with('error', 'La venta ya está anulada');
        }

        DB::beginTransaction();

        try {
            // Devolver stock
            foreach ($venta->detalles as $detalle) {
                $producto = $detalle->producto;
                $producto->stock_actual += $detalle->cantidad;
                $producto->save();
            }

            // Restar puntos del cliente
            if ($venta->cliente_id) {
                $cliente = $venta->cliente;
                $cliente->puntos_fidelidad -= $venta->puntos_otorgados;
                $cliente->total_compras -= $venta->total;
                $cliente->save();
            }

            // Anular venta
            $venta->estado = 'anulada';
            $venta->save();

            DB::commit();

            return redirect()->route('ventas.show', $venta->id)
                ->with('success', 'Venta anulada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al anular la venta: ' . $e->getMessage());
        }
    }

    /**
     * Imprimir comprobante
     */
    public function imprimir(Venta $venta)
    {
        $venta->load(['cliente', 'usuario', 'detalles.producto']);
        return view('ventas.imprimir', compact('venta'));
    }
}
