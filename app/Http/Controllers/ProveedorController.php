<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Proveedor::query();

        // Búsqueda
        if ($request->has('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('ruc', 'like', "%{$buscar}%");
            });
        }

        $proveedores = $query->orderBy('nombre')->paginate(15);

        return view('proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:150',
            'ruc' => 'required|unique:proveedores,ruc|max:20',
            'telefono' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable',
            'contacto_nombre' => 'nullable|max:100',
            'contacto_telefono' => 'nullable|max:20',
            'evaluacion' => 'nullable|numeric|min:0|max:5',
            'notas' => 'nullable',
            'activo' => 'boolean'
        ]);

        Proveedor::create($validated);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        $proveedor->load(['productos', 'pedidos']);
        return view('proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:150',
            'ruc' => 'required|max:20|unique:proveedores,ruc,' . $proveedor->id,
            'telefono' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable',
            'contacto_nombre' => 'nullable|max:100',
            'contacto_telefono' => 'nullable|max:20',
            'evaluacion' => 'nullable|numeric|min:0|max:5',
            'notas' => 'nullable',
            'activo' => 'boolean'
        ]);

        $proveedor->update($validated);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        try {
            $proveedor->delete();
            return redirect()->route('proveedores.index')
                ->with('success', 'Proveedor eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('proveedores.index')
                ->with('error', 'No se puede eliminar el proveedor porque tiene registros relacionados');
        }
    }
}
