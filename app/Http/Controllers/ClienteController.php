<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Cliente::query();

        // Búsqueda
        if ($request->has('buscar') && $request->buscar != '') {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('documento', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%");
            });
        }

        // Filtro por estado (activo/inactivo)
        if ($request->has('estado') && $request->estado != '') {
            if ($request->estado == 'activo') {
                $query->where('activo', true);
            } elseif ($request->estado == 'inactivo') {
                $query->where('activo', false);
            }
        }

        $clientes = $query->orderBy('nombre')->paginate(15);

        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:150',
            'documento' => 'required|unique:clientes,documento|max:20',
            'tipo_documento' => 'required|in:INE,CURP,RFC,PASAPORTE',
            'telefono' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable',
            'fecha_nacimiento' => 'nullable|date',
            'puntos_fidelidad' => 'nullable|integer|min:0',
            'activo' => 'boolean'
        ]);

        // Establecer puntos por defecto si no se proporciona
        if (!isset($validated['puntos_fidelidad'])) {
            $validated['puntos_fidelidad'] = 0;
        }

        Cliente::create($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        $cliente->load('ventas.detalles.producto');
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:150',
            'documento' => 'required|max:20|unique:clientes,documento,' . $cliente->id,
            'tipo_documento' => 'required|in:INE,CURP,RFC,PASAPORTE',
            'telefono' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable',
            'fecha_nacimiento' => 'nullable|date',
            'puntos_fidelidad' => 'nullable|integer|min:0',
            'activo' => 'boolean'
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        try {
            $cliente->delete();
            return redirect()->route('clientes.index')
                ->with('success', 'Cliente eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede eliminar el cliente porque tiene registros relacionados');
        }
    }

    /**
     * Mostrar historial de compras del cliente
     */
    public function historial(Request $request, Cliente $cliente)
    {
        $query = $cliente->ventas()->with('detalles.producto');

        // Filtro por fecha desde
        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->whereDate('fecha_venta', '>=', $request->fecha_desde);
        }
        
        // Filtro por fecha hasta
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->whereDate('fecha_venta', '<=', $request->fecha_hasta);
        }

        // Filtro por método de pago
        if ($request->has('metodo_pago') && $request->metodo_pago != '') {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        // Calcular estadísticas completas antes de paginar
        $totalCompras = $query->count();
        $montoTotal = $query->sum('total');

        $historial = $query->orderBy('fecha_venta', 'desc')->paginate(10);

        return view('clientes.historial', compact('cliente', 'historial', 'totalCompras', 'montoTotal'));
    }
}
