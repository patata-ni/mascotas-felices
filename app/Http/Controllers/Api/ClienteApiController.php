<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::query();

        if ($request->has('buscar')) {
            $buscar = $request->buscar;
            $query->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('documento', 'like', "%{$buscar}%");
        }

        $clientes = $query->where('activo', true)->paginate(20);

        return response()->json($clientes);
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return response()->json($cliente);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:150',
            'documento' => 'required|unique:clientes,documento|max:20',
            'tipo_documento' => 'required|in:INE,CURP,RFC,PASAPORTE',
            'telefono' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable',
        ]);

        $cliente = Cliente::create($validated);

        return response()->json($cliente, 201);
    }

    public function historial($id)
    {
        $cliente = Cliente::findOrFail($id);
        $ventas = $cliente->ventas()
            ->with('detalles.producto')
            ->orderBy('fecha_venta', 'desc')
            ->paginate(10);

        return response()->json($ventas);
    }
}
