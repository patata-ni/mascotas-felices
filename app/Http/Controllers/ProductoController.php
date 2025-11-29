<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'proveedor']);

        // Búsqueda
        if ($request->has('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%");
            });
        }

        // Filtro por categoría (acepta tanto 'categoria' como 'categoria_id')
        if (($request->has('categoria') && $request->categoria != '') || 
            ($request->has('categoria_id') && $request->categoria_id != '')) {
            $categoriaId = $request->categoria ?? $request->categoria_id;
            $query->where('categoria_id', $categoriaId);
        }

        // Filtro por stock bajo
        if ($request->has('stock_bajo') && $request->stock_bajo == '1') {
            $query->whereRaw('stock_actual <= stock_minimo');
        }

        // Mostrar solo productos activos
        $query->where('activo', true);

        $productos = $query->orderBy('nombre')->paginate(15);
        $categorias = Categoria::where('activo', true)->get();

        return view('productos.index', compact('productos', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::where('activo', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();
        return view('productos.create', compact('categorias', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|unique:productos,codigo|max:50',
            'nombre' => 'required|max:200',
            'descripcion' => 'nullable',
            'categoria_id' => 'required|exists:categorias,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'stock_maximo' => 'required|integer|min:0',
            'unidad_medida' => 'required|max:20',
            'imagen' => 'nullable|image|max:2048',
            'activo' => 'boolean'
        ]);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($validated);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        $producto->load(['categoria', 'proveedor']);
        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::where('activo', true)->get();
        $proveedores = Proveedor::where('activo', true)->get();
        return view('productos.edit', compact('producto', 'categorias', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'codigo' => 'required|max:50|unique:productos,codigo,' . $producto->id,
            'nombre' => 'required|max:200',
            'descripcion' => 'nullable',
            'categoria_id' => 'required|exists:categorias,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'stock_maximo' => 'required|integer|min:0',
            'unidad_medida' => 'required|max:20',
            'imagen' => 'nullable|image|max:2048',
            'activo' => 'boolean'
        ]);

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($validated);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     * Elimina completamente el producto y sus relaciones de la base de datos.
     */
    public function destroy(Producto $producto)
    {
        try {
            // Eliminar imagen si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            
            // Eliminar detalles de ventas asociados
            \DB::table('venta_detalles')->where('producto_id', $producto->id)->delete();
            
            // Eliminar detalles de pedidos asociados
            \DB::table('pedido_detalles')->where('producto_id', $producto->id)->delete();
            
            // Eliminar el producto
            $producto->delete();
            
            return redirect()->route('productos.index')
                ->with('success', 'Producto eliminado exitosamente junto con todos sus registros relacionados.');
        } catch (\Exception $e) {
            return redirect()->route('productos.index')
                ->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Obtener productos con stock bajo
     */
    public function stockBajo()
    {
        $productos = Producto::with(['categoria'])
            ->whereRaw('stock_actual <= stock_minimo')
            ->orderBy('stock_actual', 'asc')
            ->get();

        return view('productos.stock-bajo', compact('productos'));
    }
}
