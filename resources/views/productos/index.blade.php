@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Productos</h1>
        <p class="text-gray-600">Gestiona tu inventario de productos</p>
    </div>
    <a href="{{ route('productos.create') }}" 
       class="bg-[#190C7B] text-white px-6 py-3 rounded-lg hover:bg-[#2D1B9E] transition flex items-center">
        <i class="fas fa-plus mr-2"></i>
        Nuevo Producto
    </a>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <form method="GET" action="{{ route('productos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
            <input type="text" 
                   name="buscar" 
                   value="{{ request('buscar') }}"
                   placeholder="Nombre o código..."
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
            <select name="categoria_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                <option value="">Todas</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
            <select name="stock_bajo" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#4A3DB8]">
                <option value="">Todos</option>
                <option value="1" {{ request('stock_bajo') == '1' ? 'selected' : '' }}>Solo stock bajo</option>
            </select>
        </div>
        
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 bg-[#190C7B] text-white px-4 py-2 rounded-lg hover:bg-[#2D1B9E]">
                <i class="fas fa-search mr-2"></i> Buscar
            </button>
            <a href="{{ route('productos.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>
</div>

<!-- Tabla de Productos -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Código
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Producto
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Categoría
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Stock
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Precio Venta
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($productos as $producto)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono text-gray-900">{{ $producto->codigo }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($producto->imagen)
                                <img src="{{ Storage::url($producto->imagen) }}" 
                                     alt="{{ $producto->nombre }}"
                                     class="w-10 h-10 rounded-lg object-cover mr-3">
                            @else
                                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-box text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $producto->nombre }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($producto->descripcion, 40) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-[#EDE9FE] text-[#190C7B]">
                            {{ $producto->categoria->nombre }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($producto->stockBajo())
                            <span class="text-red-600 font-bold">{{ $producto->stock_actual }}</span>
                            <i class="fas fa-exclamation-triangle text-red-500 ml-1"></i>
                        @else
                            <span class="text-[#5B8FCC] font-bold">{{ $producto->stock_actual }}</span>
                        @endif
                        <div class="text-xs text-gray-500">Mín: {{ $producto->stock_minimo }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
                        $ {{ number_format($producto->precio_venta, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($producto->activo)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-[#EDE9FE] text-[#5B8FCC]">
                                Activo
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Inactivo
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('productos.show', $producto->id) }}" 
                               class="text-[#5B8FCC] hover:text-[#190C7B]" title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('productos.edit', $producto->id) }}" 
                               class="text-[#190C7B] hover:text-[#2D1B9E]" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('productos.destroy', $producto->id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('⚠️ ADVERTENCIA: Esto eliminará permanentemente el producto y TODOS sus registros relacionados (ventas, pedidos, etc.). Esta acción NO se puede deshacer. ¿Estás completamente seguro?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i class="fas fa-box-open text-gray-400 text-4xl mb-3"></i>
                        <p class="text-gray-500">No se encontraron productos</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($productos->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200">
        {{ $productos->links() }}
    </div>
    @endif
</div>
@endsection
